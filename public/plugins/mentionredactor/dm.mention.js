/*
 * Mention
 * Version 1.0.0
 * Written by: Daniel Muela
 *
 */

(function ($, _, undefined) {

  // Settings
  var KEY = { BACKSPACE : 8, TAB : 9, RETURN : 13, ESC : 27, LEFT : 37, UP : 38, RIGHT : 39, DOWN : 40, COMMA : 188, SPACE : 32, HOME : 36, END : 35 }; // Keys "enum"
  
  //Default settings
  var defaultSettings = {
    triggerChar   : '@', //Char that respond to event
    hashtag       : false,
    wiki       : false,
    timer       : null,
    onDataRequest : $.noop, //Function where we can search the data
    minChars      : 3, //Minimum chars to fire the event
    allowRepeat   : false, //Allow repeat mentions
    showAvatars   : false, //Show the avatars
    redactor      : true,
    onCaret       : false,
    classes       : { 
      autoCompleteItemActive : "active" //Classes to apply in each item
    },
    templates     : {
      wrapper                    : _.template('<div class="mentions-input-box"></div>'),
      autocompleteList           : _.template('<div class="mentions-autocomplete-list"></div>'),
      autocompleteListItem       : _.template('<li data-ref-id="<%= id %>" data-ref-type="<%= type %>" data-display="<%= display %>"><%= content %></li>'),
      autocompleteListItemAvatar : _.template('<img src="<%= avatar %>" />'),
      autocompleteListItemIcon   : _.template('<div class="icon <%= icon %>"></div>'),
      mentionItemSyntax          : _.template('@[<%= value %>](<%= type %>:<%= id %>)'),
      link                       : _.template('<a href="#<%= type %>-<%= id %>" class="<%= type %>"><%= value %></a>'),
    }
  };

  //Class util
  var utils = {
	//Encodes the character with _.escape function (undersocre)
    htmlEncode       : function (str) {
      return _.escape(str);
    },
    //Encodes the character to be used with RegExp
    regexpEncode     : function (str) {
      return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    },
	//
    highlightTerm    : function (value, term) {
      if (!term && !term.length) {
        return value;
      }
      return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<b>$1</b>");
    },
	//Sets the caret in a valid position
    setCaratPosition : function (domNode, caretPos) {
      $('#content').redactor('caret.setOffset', caretPos);
    },
	//Deletes the white spaces
    rtrim: function(string) {
      return string.replace(/\s+$/,"");
    }
  };

  //Main class of MentionsInput plugin
  var MentionsInput = function (settings) {

    var domInput, 
		elmInputBox, 
		elmInputWrapper, 
		elmAutocompleteList, 
		elmWrapperBox, 
		elmMentionsOverlay, 
		elmActiveAutoCompleteItem,
		mentionsCollection = [],
		autocompleteItemCollection = {},
		inputBuffer = [],
		currentDataQuery = '',
    currentTrigger = '';

	//Mix the default setting with the users settings
    settings = $.extend(true, {}, defaultSettings, settings );

	//Initializes the text area target
    function initTextarea() {
      elmInputBox = $(domInput); //Get the text area target

	  //If the text area is already configured, return
      if (elmInputBox.attr('data-mentions-input') === 'true') {
        return;
      }

      elmInputWrapper = elmInputBox.parent(); //Get the DOM element parent
      elmWrapperBox = $(settings.templates.wrapper()); 
      elmInputBox.wrapAll(elmWrapperBox); //Wrap all the text area into the div elmWrapperBox
      elmWrapperBox = elmInputWrapper.find('> div.mentions-input-box'); //Obtains the div elmWrapperBox that now contains the text area

      elmInputBox.attr('data-mentions-input', 'true'); //Sets the attribute data-mentions-input to true -> Defines if the text area is already configured
      elmInputBox.on('keydown.mention',function(e, param){
        onInputBoxKeyDown(param);
      });
      $(document).on('redactor.keypress',function(e, param){
        onInputBoxKeyPress(param);
      });
      elmInputBox.on('click.mention',function(e){
        onInputBoxClick(e);
      });
      elmInputBox.on('blur.mention',function(e){
        onInputBoxBlur(e);
      });


      if (navigator.userAgent.indexOf("MSIE 8") > -1) {
        elmInputBox.on('keyinput.mention',function(){
          onInputBoxInput();
        });
      } else {
        elmInputBox.on('keyinput.mention',function(){
          onInputBoxInput();
        });
      }
    }

	//Initializes the autocomplete list, append to elmWrapperBox and delegate the mousedown event to li elements
    function initAutocomplete() {
      elmAutocompleteList = $(settings.templates.autocompleteList()); //Get the HTML code for the list
      elmAutocompleteList.appendTo(elmWrapperBox); //Append to elmWrapperBox element
      elmAutocompleteList.delegate('li', 'mousedown', onAutoCompleteItemClick); //Delegate the event
    }

	//Initializes the mentions' overlay
    function initMentionsOverlay() {
      
    }

	//Updates the values of the main variables
    function updateValues() {
      var syntaxMessage = getInputBoxValue(); //Get the actual value of the text area

      _.each(mentionsCollection, function (mention) {
        var textSyntax = settings.templates.mentionItemSyntax(mention);
        syntaxMessage = syntaxMessage.replace(new RegExp(utils.regexpEncode(mention.value), 'g'), textSyntax);
      });

      var mentionText = utils.htmlEncode(syntaxMessage); //Encode the syntaxMessage

      mentionText = mentionText.replace(/\n/g, '<br />'); //Replace the escape character for <br />
      mentionText = mentionText.replace(/ {2}/g, '&nbsp; '); //Replace the 2 preceding token to &nbsp; 

      elmInputBox.data('messageText', syntaxMessage); //Save the messageText to elmInputBox
      // elmInputBox.trigger('updated');
    }

	//Cleans the buffer
    function resetBuffer() {
      inputBuffer = [];
    }

	//Updates the mentions collection
    function updateMentionsCollection() {
      var inputText = getInputBoxValue(); //Get the actual value of text area

	  //Returns the values that doesn't match the condition
      mentionsCollection = _.reject(mentionsCollection, function (mention, index) {
        return !mention.value || inputText.indexOf(mention.value) == -1;
      });
      mentionsCollection = _.compact(mentionsCollection); //Delete all the falsy values of the array and return the new array
    }

	//Adds mention to mentions collections
    function addMention(mention) {

      var originalvalue = mention.value;
      mention.value = settings.templates.link(mention);

      var currentMessage = getInputBoxValue(); //Get the actual value of the text area
      var mensajelimpio = limpiar(currentMessage);

      // Using a regex to figure out positions
      var regex = new RegExp("\\" + currentTrigger + currentDataQuery, "gi");
      regex.exec(currentMessage); //Executes a search for a match in a specified string. Returns a result array, or null

      var startCaretPosition = regex.lastIndex - currentDataQuery.length - 1; //Set the star caret position
      var currentCaretPosition = regex.lastIndex; //Set the current caret position

      var start = currentMessage.substr(0, startCaretPosition);
      var end = currentMessage.substr(currentCaretPosition, currentMessage.length);

      // FOLKLORE
      var regex = new RegExp("\\" + currentTrigger + currentDataQuery, "gi");
      regex.exec(mensajelimpio); //Executes a search for a match in a specified string. Returns a result array, or null

      var dmstartCaretPosition = regex.lastIndex - currentDataQuery.length - 1; //Set the star caret position
      var dmcurrentCaretPosition = regex.lastIndex; //Set the current caret position
      var dmstart = mensajelimpio.substr(0, dmstartCaretPosition);
      var dmend = mensajelimpio.substr(dmcurrentCaretPosition, mensajelimpio.length);  
      var startEndIndex = (dmstart + originalvalue).length + 1;

      // See if there's the same mention in the list
      if( !_.find(mentionsCollection, function (object) { return object.id == mention.id; }) ) {
        mentionsCollection.push(mention);//Add the mention to mentionsColletions
      }

      // Cleaning before inserting the value, otherwise auto-complete would be triggered with "old" inputbuffer
      resetBuffer();
      currentDataQuery = '';
      currentTrigger = '';
      hideAutoComplete();

      // Mentions and syntax message
      var updatedMessageText = start + mention.value + ' ' + end;
      elmInputBox.redactor('insert.set','');
      elmInputBox.redactor('insert.htmlWithoutClean',updatedMessageText);

	  // elmInputBox.trigger('mention');
      updateValues();

      // Set correct focus and selection
      elmInputBox.focus();
      utils.setCaratPosition(elmInputBox[0], startEndIndex);
    }

	//Gets the actual value of the text area without white spaces from the beginning and end of the value
    function getInputBoxValue() {
      var text = elmInputBox.redactor('code.get');
      return $.trim(text);
    }

    // Folklore
    function limpiar(limpia) {
      var text = limpia;
      text = elmInputBox.redactor('clean.getPlainText',text);
      text = elmInputBox.redactor('clean.stripTags',text);
      return $.trim(text);
    }
	
	// This is taken straight from live (as of Sep 2012) GitHub code. The
    // technique is known around the web. Just google it. Github's is quite
    // succint though. NOTE: relies on selectionEnd, which as far as IE is concerned,
    // it'll only work on 9+. Good news is nothing will happen if the browser
    // doesn't support it.
    function textareaSelectionPosition($el) {
      var a, b, c, d, e, f, g, h, i, j, k;
      if (!(i = $el[0])) return;
      if (!$(i).is("textarea")) return;
      if (i.selectionEnd == null) return;
      g = {
        position: "absolute",
        overflow: "auto",
        whiteSpace: "pre-wrap",
        wordWrap: "break-word",
        boxSizing: "content-box",
        top: 0,
        left: -9999
      }, h = ["boxSizing", "fontFamily", "fontSize", "fontStyle", "fontVariant", "fontWeight", "height", "letterSpacing", "lineHeight", "paddingBottom", "paddingLeft", "paddingRight", "paddingTop", "textDecoration", "textIndent", "textTransform", "width", "word-spacing"];
      for (j = 0, k = h.length; j < k; j++) e = h[j], g[e] = $(i).css(e);
      return c = document.createElement("div"), $(c).css(g), $(i).after(c), b = document.createTextNode(i.value.substring(0, i.selectionEnd)), a = document.createTextNode(i.value.substring(i.selectionEnd)), d = document.createElement("span"), d.innerHTML = "&nbsp;", c.appendChild(b), c.appendChild(d), c.appendChild(a), c.scrollTop = i.scrollTop, f = $(d).position(), $(c).remove(), f
    }

  //Scrolls back to the input after autocomplete if the window has scrolled past the input
    function scrollToInput() {
      var elmDistanceFromTop = $(elmInputBox).offset().top; //input offset
      var bodyDistanceFromTop = $('body').offset().top; //body offset
      var distanceScrolled = $(window).scrollTop(); //distance scrolled

      if (distanceScrolled > elmDistanceFromTop) {
        //subtracts body distance to handle fixed headers
        $(window).scrollTop(elmDistanceFromTop - bodyDistanceFromTop);
      }

    }

	//Takes the click event when the user select a item of the dropdown
    function onAutoCompleteItemClick(e) {
      var elmTarget = $(this); //Get the item selected
      var mention = autocompleteItemCollection[elmTarget.attr('data-uid')]; //Obtains the mention

      addMention(mention);

      scrollToInput();

      return false;
    }

	//Takes the click event on text area
    function onInputBoxClick(e) {
      resetBuffer();
    }

	//Takes the blur event on text area
    function onInputBoxBlur(e) {
      hideAutoComplete();
    }

	//Takes the input event when users write or delete something
    function onInputBoxInput() {
      updateValues();
      updateMentionsCollection();

      var triggerCharIndex = _.lastIndexOf(inputBuffer, settings.triggerChar); //Returns the last match of the triggerChar in the inputBuffer
      if (triggerCharIndex > -1) { //If the triggerChar is present in the inputBuffer array
        currentTrigger = settings.triggerChar;
        currentDataQuery = inputBuffer.slice(triggerCharIndex + 1).join(''); //Gets the currentDataQuery
        currentDataQuery = utils.rtrim(currentDataQuery); //Deletes the whitespaces

        _.defer(_.bind(doSearch, this, currentDataQuery, settings.triggerChar)); //Invoking the function doSearch ( Bind the function to this)
      }

      //Hashtag
      var hashtagIndex = _.lastIndexOf(inputBuffer, settings.hashtag); //Returns the last match of the hashtag in the inputBuffer
      if (hashtagIndex > -1) { //If the hashtag is present in the inputBuffer array
        currentTrigger = settings.hashtag;
        currentDataQuery = inputBuffer.slice(hashtagIndex + 1).join(''); //Gets the currentDataQuery
        currentDataQuery = utils.rtrim(currentDataQuery); //Deletes the whitespaces

        _.defer(_.bind(doSearch, this, currentDataQuery, settings.hashtag)); //Invoking the function doSearch ( Bind the function to this)
      }

      //Wiki
      var wikiIndex = _.lastIndexOf(inputBuffer, settings.wiki); //Returns the last match of the wiki in the inputBuffer
      if (wikiIndex > -1) { //If the wiki is present in the inputBuffer array
        currentTrigger = settings.wiki;
        currentDataQuery = inputBuffer.slice(wikiIndex + 1).join(''); //Gets the currentDataQuery
        currentDataQuery = utils.rtrim(currentDataQuery); //Deletes the whitespaces

        _.defer(_.bind(doSearch, this, currentDataQuery, settings.wiki)); //Invoking the function doSearch ( Bind the function to this)
      }


    }

	//Takes the keypress event
    function onInputBoxKeyPress(e) {
      if(e.keyCode !== KEY.BACKSPACE) { //If the key pressed is not the backspace
        var typedValue = String.fromCharCode(e.which || e.keyCode); //Takes the string that represent this CharCode
        inputBuffer.push(typedValue); //Push the value pressed into inputBuffer
      }
    }

	//Takes the keydown event
    function onInputBoxKeyDown(e) {

      // This also matches HOME/END on OSX which is CMD+LEFT, CMD+RIGHT
      if (e.keyCode === KEY.HOME || e.keyCode === KEY.END) {
        // Defer execution to ensure carat pos has changed after HOME/END keys then call the resetBuffer function
        _.defer(resetBuffer);

        // IE9 doesn't fire the oninput event when backspace or delete is pressed. This causes the highlighting
        // to stay on the screen whenever backspace is pressed after a highlighed word. This is simply a hack
        // to force updateValues() to fire when backspace/delete is pressed in IE9.
        if (navigator.userAgent.indexOf("MSIE 9") > -1) {
          _.defer(updateValues); //Call the updateValues function
        }

        return;
      }

	  //If the key pressed was the backspace
      if (e.keyCode === KEY.BACKSPACE) {
        inputBuffer = inputBuffer.slice(0, -1 + inputBuffer.length); // Can't use splice, not available in IE
        return;
      }

	  //If the elmAutocompleteList is hidden
      if (!elmAutocompleteList.is(':visible')) {
        return true;
      }

      switch (e.keyCode) {
        case KEY.UP: //If the key pressed was UP or DOWN
        case KEY.DOWN:
        case KEY.LEFT:
        case KEY.RIGHT:
          var elmCurrentAutoCompleteItem = null;
          if (e.keyCode === KEY.DOWN || e.keyCode === KEY.RIGHT) { //If the key pressed was DOWN
            if (elmActiveAutoCompleteItem && elmActiveAutoCompleteItem.length) { //If elmActiveAutoCompleteItem exits 
              elmCurrentAutoCompleteItem = elmActiveAutoCompleteItem.next(); //Gets the next li element in the list
            } else {
              elmCurrentAutoCompleteItem = elmAutocompleteList.find('li').first(); //Gets the first li element found
            }
          } else {
            elmCurrentAutoCompleteItem = $(elmActiveAutoCompleteItem).prev(); //The key pressed was UP and gets the previous li element
          }

          if (elmCurrentAutoCompleteItem.length) {
            selectAutoCompleteItem(elmCurrentAutoCompleteItem);
          }

          return false;

        case KEY.RETURN: //If the key pressed was RETURN or TAB
        case KEY.TAB:
          if (elmActiveAutoCompleteItem && elmActiveAutoCompleteItem.length) { //If the elmActiveAutoCompleteItem exists
            elmActiveAutoCompleteItem.trigger('mousedown'); //Calls the mousedown event
            return false;
          }

          break;
      }

      return true;
    }

	//Hides the autoomplete
    function hideAutoComplete() {
      elmActiveAutoCompleteItem = null;
      elmAutocompleteList.empty().hide();
    }

	//Selects the item in the autocomplete list
    function selectAutoCompleteItem(elmItem) {
      elmItem.addClass(settings.classes.autoCompleteItemActive); //Add the class active to item
      elmItem.siblings().removeClass(settings.classes.autoCompleteItemActive); //Gets all li elements in autocomplete list and remove the class active

      elmActiveAutoCompleteItem = elmItem; //Sets the item to elmActiveAutoCompleteItem
    }

	//Populates dropdown
    function populateDropdown(query, results) {
      elmAutocompleteList.show(); //Shows the autocomplete list

      if(!settings.allowRepeat) {
        // Filter items that has already been mentioned
        var mentionValues = _.pluck(mentionsCollection, 'value');
        results = _.reject(results, function (item) {
          return _.include(mentionValues, item.name);
        });
      }

      if (!results.length) { //If there are not elements hide the autocomplete list
        hideAutoComplete();
        return;
      }

      elmAutocompleteList.empty(); //Remove all li elements in autocomplete list
      var elmDropDownList = $("<ul>").appendTo(elmAutocompleteList).hide(); //Inserts a ul element to autocomplete div and hide it

      _.each(results, function (item, index) {
        var itemUid = _.uniqueId('mention_'); //Gets the item with unique id

        autocompleteItemCollection[itemUid] = _.extend({}, item, {value: item.name}); //Inserts the new item to autocompleteItemCollection Folklore

        var elmListItem = $(settings.templates.autocompleteListItem({
          'id'      : utils.htmlEncode(item.id),
          'display' : utils.htmlEncode(item.name),
          'type'    : utils.htmlEncode(item.type),
          'content' : utils.highlightTerm(utils.htmlEncode((item.display ? item.display : item.name)), query)
        })).attr('data-uid', itemUid); //Inserts the new item to list

		//If the index is 0
        if (index === 0) {
          selectAutoCompleteItem(elmListItem);
        }

		//If show avatars is true
        if (settings.showAvatars) {
          var elmIcon;

		  //If the item has an avatar
          if (item.avatar) {
            elmIcon = $(settings.templates.autocompleteListItemAvatar({ avatar : item.avatar }));
          } else { //If not then we set an default icon
            elmIcon = $(settings.templates.autocompleteListItemIcon({ icon : item.icon }));
          }
          elmIcon.prependTo(elmListItem); //Inserts the elmIcon to elmListItem
        }
        elmListItem = elmListItem.appendTo(elmDropDownList); //Insets the elmListItem to elmDropDownList
      });

      elmAutocompleteList.show(); //Shows the elmAutocompleteList div
	  if (settings.onCaret) {
		positionAutocomplete(elmAutocompleteList, elmInputBox);
      }
	  elmDropDownList.show(); //Shows the elmDropDownList
    }

	//Search into data list passed as parameter
    function doSearch(query, trigger) {
	  //If the query is not null, undefined, empty and has the minimum chars

      if (query && query.length && query.length >= settings.minChars) {
		//Call the onDataRequest function and then call the populateDropDrown
        if (settings.timer) { 
            clearTimeout(settings.timer);
        } 
        settings.timer = setTimeout(function() { 
            settings.timer = null;
            settings.onDataRequest.call(this, 'search', query, trigger, function (responseData) {
              populateDropdown(query, responseData);
            });
        }, 600);
      } else { //If the query is null, undefined, empty or has not the minimun chars
        hideAutoComplete(); //Hide the autocompletelist
      }
    }
	
	function positionAutocomplete(elmAutocompleteList, elmInputBox) {
      // var position = textareaSelectionPosition(elmInputBox),
      //     lineHeight = parseInt(elmInputBox.css('line-height'), 10) || 18;
      // elmAutocompleteList.css('width', '15em'); // Sort of a guess
      // elmAutocompleteList.css('left', position.left);
      // elmAutocompleteList.css('top', lineHeight + position.top);
    }

	//Resets the text area
    function resetInput() {
      elmInputBox.val('');
      mentionsCollection = [];
      updateValues();
    }

    // Public methods
    return {
      //Initializes the mentionsInput component on a specific element.
	  init : function (domTarget) {

        domInput = domTarget;

        initTextarea();
        initAutocomplete();
        initMentionsOverlay();
        resetInput();

		//If the autocomplete list has prefill mentions
        if( settings.prefillMention ) {
          addMention( settings.prefillMention );
        }

      },

	  //An async method which accepts a callback function and returns a value of the input field (including markup) as a first parameter of this function. This is the value you want to send to your server.
      val : function (callback) {
        if (!_.isFunction(callback)) {
          return;
        }

        callback.call(this, mentionsCollection.length ? elmInputBox.data('messageText') : getInputBoxValue());
      },

      sugerencias : function (callback) { //Folklore
        return (elmActiveAutoCompleteItem && elmActiveAutoCompleteItem.length) ? true : false;
      },

	  //Resets the text area value and clears all mentions
      reset : function () {
        resetInput();
      },

	  //An async method which accepts a callback function and returns a collection of mentions as hash objects as a first parameter.
      getMentions : function (callback) {
        if (!_.isFunction(callback)) {
          return;
        }

        callback.call(this, mentionsCollection);
      }
    };
  };

  //Main function to include into jQuery and initialize the plugin
  $.fn.mentionsInput = function (method, settings) {

    var outerArguments = arguments; //Gets the arguments

	//If method is not a function
    if (typeof method === 'object' || !method) {
      settings = method;
    }

    return this.each(function () {
      var instance = $.data(this, 'mentionsInput') || $.data(this, 'mentionsInput', new MentionsInput(settings));

      if (_.isFunction(instance[method])) {
        return instance[method].apply(this, Array.prototype.slice.call(outerArguments, 1));

      } else if (typeof method === 'object' || !method) {
        return instance.init.call(this, this);

      } else {
        $.error('Method ' + method + ' does not exist');
      }

    });
  };

})(jQuery, _);
