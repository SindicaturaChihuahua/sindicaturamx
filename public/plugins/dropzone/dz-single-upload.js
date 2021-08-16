;(function(){
	var myDropzone;
	
	myDropzone = new Dropzone( '.oic-uploadfiles', {
		url: DMGConfig.sigaAjax+'objectimage.php',
		maxFilesize: 12,
		previewsContainer: '.oic-preview',
		maxFiles: 1,
		acceptedFiles: 'image/*',
		thumbnailWidth: 140,
		thumbnailHeight: 90,
		uploadMultiple: false,
		accept: function(file, done){
		if(file.name != "justinbieber.jpg"){
			done("Naha, you don't.");
		}else{ 
			done(); 
		}
	  }
	});
	
	myDropzone.on("addedfile", function(file) {
		console.log("what");
	  // Hookup the start button
	  //file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
	});
	 
	// Update the total progress bar
	myDropzone.on("totaluploadprogress", function(progress) {
	  document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
	});
	 
	myDropzone.on("sending", function(file) {
	  // Show the total progress bar when upload starts
	  document.querySelector("#total-progress").style.opacity = "1";
	  // And disable the start button
	  file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
	});
	 
	// Hide the total progress bar when nothing's uploading anymore
	myDropzone.on("queuecomplete", function(progress) {
	  document.querySelector("#total-progress").style.opacity = "0";
	});

})()