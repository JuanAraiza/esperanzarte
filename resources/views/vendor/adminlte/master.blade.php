@php
    $layoutFixed = config('adminlte.layout_fixed_sidebar');
    $fixedHeader = config('adminlte.layout_fixed_navbar');
    $fixedFooter = config('adminlte.layout_fixed_footer');
    $rtl = config('adminlte.layout_rtl', false);
    $sidebarBreakpoint = config('adminlte.sidebar_breakpoint', 'lg');
    $sidebarMini = config('adminlte.sidebar_mini');
    $sidebarCollapse = config('adminlte.sidebar_collapse');

    $bodyClasses = collect([
        $layoutFixed ? 'layout-fixed' : null,
        $fixedHeader ? 'fixed-header' : null,
        $fixedFooter ? 'fixed-footer' : null,
        'sidebar-expand-'.$sidebarBreakpoint,
        $sidebarMini ? 'sidebar-mini' : null,
        $sidebarCollapse ? 'sidebar-collapse' : null,
        'bg-body-tertiary',
        config('adminlte.classes_body'),
    ])->filter()->implode(' ');

    $titlePrefix = config('adminlte.title_prefix', '');
    $titlePostfix = config('adminlte.title_postfix', '');
    $title = trim($titlePrefix.' '.($title ?? config('adminlte.title', 'AdminLTE 4')).' '.$titlePostfix);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-bs-theme="light" style="color-scheme: light; height: auto;">
<head>
    <script>
    document.documentElement.removeAttribute('data-bs-theme');
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/palomita.png">
    <title>{{ $title }}</title>

    @hasSection('adminlte_css')
        @yield('adminlte_css')
    @endif

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    {{-- Compiled AdminLTE + Bootstrap from your Vite pipeline --}}
    @vite(['resources/css/adminlte.css', 'resources/js/adminlte.js'])

    @if ($rtl)
        {{-- AdminLTE ships a prebuilt RTL stylesheet; published by adminlte:install. --}}
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.rtl.min.css') }}">
    @endif

    <!--<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">-->
    <!--<link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">-->

<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('css')
    @yield('css')
    @pluginStyles
    
</head>
<body class="{{ $bodyClasses }}">
    @include('adminlte::partials.preloader')

    @include('adminlte::partials.impersonation-banner')

    <div class="app-wrapper">
        @include('adminlte::partials.navbar')
        @include('adminlte::partials.sidebar')

        <main class="app-main">
            @hasSection('content_header')
                <div class="app-content-header {{ config('adminlte.classes_content_header') }}">
                    <div class="container-fluid">
                        @yield('content_header')
                    </div>
                </div>
            @endif

            <div class="app-content {{ config('adminlte.classes_content') }}">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        @include('adminlte::partials.footer')
        @include('adminlte::partials.control-sidebar')
    </div>

    @pluginScripts
    @stack('js')
    @yield('js')
     @if (session('swal'))
    <script>
       // console.log("dfsdf");
        Swal.fire(@json(session('swal')));


    </script>
  
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title:'Oops...',
                html: `
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
                `
            })
            </script>
    @endif
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <!--<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>-->
                <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
                <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    <script>
  /*

$(document).ready(function() {
  $('select').not('.select2-hidden-accessible').select2({
        theme: 'bootstrap4',
        width: '100%',
    });
});
*/
        function preview_image(event, querySelector){

	//Recuperamos el input que desencadeno la acción
	let input = event.target;
	
	//Recuperamos la etiqueta img donde cargaremos la imagen
	let imgPreview = document.querySelector(querySelector);

	// Verificamos si existe una imagen seleccionada
	if(!input.files.length) return
	
	//Recuperamos el archivo subido
	let file = input.files[0];

	//Creamos la url
	let objectURL = URL.createObjectURL(file);
	
	//Modificamos el atributo src de la etiqueta img
	imgPreview.src = objectURL;
                
}

function valida(e){

    

    tecla = (document.all) ? e.keyCode : e.which;
  
    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
  if (tecla==46){
        return '.';
    }
  if (tecla==58){
        return ':';
    }
  
     if (tecla==0){
       
    }   
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

//const tema = 'light'; // Puede ser 'dark', 'light' o 'auto'

//document.querySelector(`[data-bs-theme-value="light"]`).click();


document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('[data-bs-theme-value="light"]').click();
});


        </script>
</body>
</html>
