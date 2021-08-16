var mmap, exml, sidebar;
$(document).ready(function(e){
    beans();
    $(function(){
        if(page !== "nuestraciudad"){
            $(document).tooltip({
                position:{
                    my: "center center",
                    at: "center top"
                }
            });
        }
    });
});

function beans(){
	owlAliados = $('.owlAliados');
	owlAliados.owlCarousel({
		items:4,
		loop:true,
		margin:0,
		autoplay:true,
		autoplayTimeout:5000,
		autoplayHoverPause:true,
		stagePadding:0,
		nav:true,
		dots:false,
		merge:false,
		navText:['<i class="fas fa-long-arrow-alt-left"></i>','<i class="fas fa-long-arrow-alt-right"></i>'],
		responsive:{
			0:{
				items:1,
			},
			640:{
				items:2,
			},
			800:{
				items:3,
			},
			1062:{
				items:4
			}
		}
	});


    $('a.scrollpage').click(function(e){
        e.preventDefault();
        var goto=$('#'+$(this).data('goto')).offset().top-78;
        if(goto && goto>=-78){
            $("html, body").animate({scrollTop: goto},700);
        }
    });

    if(page == "home"){
        owlHero = $('.owlHero');
        owlHero.owlCarousel({
            items:1,
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:7000,
            autoplayHoverPause:true,
            nav:false,
            dots:false
        });

        owlCarousel = $('.owlCarousel');
        owlCarousel.owlCarousel({
            items:1,
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:7000,
            autoplayHoverPause:true,
            nav:false,
            dots:true
        });
        $('.noticias-cintillo').marquee({
            speed: 85,
            startVisible:false,
            duplicated:true,
            pauseOnHover:true,
        });
        var i = false;
        $(".home-top-noticias").hover(function(){
            $('.noticias-cintillo').marquee('pause');
        }, function() {
            $('.noticias-cintillo').marquee('resume');
        });
        owlHomeEnlaces = $('.owlHomeEnlaces');
        owlHomeEnlaces.owlCarousel({
            items:4,
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true,
            stagePadding:100,
            nav:true,
            dots:false,
            merge:true,
            navText:['<i class="fas fa-long-arrow-alt-left"></i>','<i class="fas fa-long-arrow-alt-right"></i>'],
            responsive:{
                0:{
                    items:1,
                    stagePadding:30,
                },
                640:{
                    items:2,
                    stagePadding:30,
                },
                800:{
                    items:3,
                    stagePadding:70,
                },
                1062:{
                    items:4,
                    stagePadding:100,
                }
            }
        });
    }
    if(page == "transparencia"){
        owlCarousel = $('.owlCarousel');
        owlCarousel.owlCarousel({
            items:1,
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:7000,
            autoplayHoverPause:true,
            nav:false,
            dots:true
        });
    }
    if(page == "contacto" || page == "ayuntamiento-comisiones"){
        $("body").on("ObjectFormDone", function(e, data){
			clear_form_elements($(".object-ajax"));
		});
    }
    if(page == "ayuntamiento-calendario"){
        if(hP == true && hF !== true){
			 $('.pasado').addClass('pasadoMostrar');
		 }else if(hP == true && hF == true){
			 $('.mPasados').fadeIn();
		 }

		 $('#mostrarPasados').click(function(){
			 $(this).toggleClass('mPactive')
			 $('.pasado').toggleClass('pasadoMostrar');
		 })
    }
    if(page == "noticias"){
        owlNota = $('.owlNota');
        owlNota.owlCarousel({
            items:1,
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:6000,
            autoplayHoverPause:true,
            autoHeight:true,
            nav:false,
            dots:true
        });
    }
    $("body").delegate( ".open-miembro", "click", function(e) {
        e.preventDefault();
        var miembro = $(this).data("open");
        $('body,html').addClass("no-scrollbars");
        $('body').prepend('<div class="modal-overlay"></div>');
        $('.modal-overlay').fadeIn(700, function(){
            $('.'+miembro).fadeIn();
        });
    });
    $("body").delegate( ".modal-cerrar", "click", function(e) {
        e.preventDefault();
        $('.modal-miembro').fadeOut(400, function(){
            $('.modal-overlay').fadeOut(700, function(){
                $('body,html').removeClass("no-scrollbars");
                $('.modal-overlay').remove();
            });
        });
    });

    if(page == "nuestraciudad"){
        sidebar = $('.obra-lista');
        var lastOpen = '';
        var lastOpenS = '';
        var lastOpenO = '';

        $('.abrir-year').click(function(){
            var y = $(this).data("open");
            if(lastOpen == y){
                $('.a-'+y).removeClass("open");
                lastOpen = '';
            }else{
                $('.obras').removeClass("open");
                $('.a-'+y).addClass("open");
                lastOpen = y;
            }
            $('.obras .obras-contenido').slideUp();
            $('.obras.open .obras-contenido').slideDown();

        });
        $('.abrir-seccion').click(function(){
            var seccion = $(this).data("open");
            if(lastOpenS == seccion){
                $('.os-'+seccion).removeClass("open");
                lastOpenS = '';
            }else{
                $('.obras-seccion').removeClass("open");
                $('.os-'+seccion).addClass("open");
                lastOpenS = seccion;
            }
            $('.obras-seccion .obras-seccion-contenido').slideUp();
            $('.obras-seccion.open .obras-seccion-contenido').slideDown();

        });
        $('.abrir-obra').click(function(){
            var id = $(this).data("open");
            var archivo = $(this).data("archivo");
            $('.obra').removeClass("open");
            $('.ow-'+id).addClass("open");
            lastOpenO = id;
            cargarMapa(id,archivo);
        });


		mmap = new google.maps.Map(document.getElementById("mapa"), {
			center: { lat: 28.6709132, lng: -106.1346581 },
			zoom: 8,
		});

        function cargarMapa(id,mapa){
            mmap.innerHTML = '';
            sidebar.html('');
            mmap=new google.maps.Map(document.getElementById("mapa"), {
				center: { lat: 0, lng: 0 },
				zoom: 8,
			});

    		exml = new EGeoXml("exml", mmap, DMGConfig.urlcargas+"obras/obj"+id+"/"+mapa, {sidebarid:"o-"+id,sortbyname:true,directions:true});
    		exml.parse();
        }

        $(".obras-search-btn").click(function(){
            var id = $(this).data("id");
            var busqueda = $('.obras-search-input.s-'+id).val();
            var filtrados = $(".obra-lista.ol-"+id+" div a").filter(function () {
                var reg = new RegExp(busqueda, "ig");
                return reg.test($(this).text())
            });
            $( ".obra-lista.ol-"+id+" div a" ).addClass("ocultar");
            filtrados.removeClass("ocultar");
        });
        $(".obras-clear-btn").click(function(){
            var id = $(this).data("id");
            $(".obra-lista.ol-"+id+" div a").removeClass("ocultar");
            $('.obras-search-input.s-'+id).val("");
        });


        $('.obras-search-input').keyup(function(e){
            var code = e.key;
            if(code==="Enter"){
                e.preventDefault();
                var id = $(this).data("id");
                document.getElementById("btns-"+id).click();
            }
        });

    }
}

function pad(pad, str, padLeft) {
    //Ejemplo: var paddednumber = pad('00',1,true);
    if (typeof str === 'undefined')
        return pad;
    if (padLeft) {
        return (pad + str).slice(-pad.length);
    } else {
        return (str + pad).substring(0, pad.length);
    }
}
function number_format(number) {
    return number.toLocaleString();
}
// function number_format(number, decimals, dec_point, thousands_sep) {
// // *     example: number_format(1234.56, 2, ',', ' ');
// // *     return: '1 234,56'
//     number = (number + '').replace(',', '').replace(' ', '');
//     var n = !isFinite(+number) ? 0 : +number,
//             prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
//             sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
//             dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
//             s = '',
//             toFixedFix = function (n, prec) {
//                 var k = Math.pow(10, prec);
//                 return '' + Math.round(n * k) / k;
//             };
//     // Fix for IE parseFloat(0.55).toFixed(0) = 0;
//     s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
//     if (s[0].length > 3) {
//         s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
//     }
//     if ((s[1] || '').length < prec) {
//         s[1] = s[1] || '';
//         s[1] += new Array(prec - s[1].length + 1).join('0');
//     }
//     return s.join(dec);
// }
