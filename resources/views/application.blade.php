<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prime Insurance</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('loader.css') }}" />
  @vite(['resources/ts/main.ts'])
</head>

<body>
  <div id="app">
    <div id="loading-bg">
      <div class="loading-logo">
        <!-- svg logo -->
          <svg width="85" height="54" viewBox="0 0 85 54" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M55.2673 7.96036L56.2158 1.76469C71.0545 2.43677 77.3637 7.64533 78.6635 10.1656C75.1224 7.30929 61.5906 7.50531 55.2673 7.96036Z" fill="#CD1414"/>
              <path d="M60.7475 0.819591C65.771 0.99461 77.4199 3.61289 83.8275 12.6859C83.5816 8.55543 78.6213 0.399546 60.7475 0.819591Z" fill="#CD1414"/>
              <path d="M41.0829 54H27.949L36.7302 19.9677C22.2671 15.5837 10.1235 16.1043 0 18.2049C5.17254 15.2565 19.8733 11.8419 37.5068 16.958L37.9289 15.3221C28.3539 12.8607 17.0573 11.7048 3.78257 14.8458C9.10371 12.5201 23.5039 8.81403 39.1222 10.697L40.1373 6.76309H34.9888L41.0829 0.149918H52.7459L49.9714 12.9604C50.5117 13.1255 51.0514 13.299 51.5901 13.4812C61.3126 16.7703 72.9196 23.5584 84.3724 17.6801C83.6719 20.1994 78.9087 25.5529 68.8218 25.133C63.5723 24.9145 56.9567 21.7846 48.7172 18.7509L48.267 20.8297C61.3671 25.8389 69.139 28.3049 80.0645 25.133C77.6478 28.3521 68.2224 34.3853 52.7459 26.4976C50.9934 25.6045 49.2696 24.783 47.5741 24.029L41.0829 54Z" fill="#CD1414"/>
          </svg>
      </div>
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
  </div>
  
  <script>
    const loaderColor = localStorage.getItem('materialize-initial-loader-bg') || '#FFFFFF'
    const primaryColor = localStorage.getItem('materialize-initial-loader-color') || '#666CFF'

    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)

    if (primaryColor)
      document.documentElement.style.setProperty('--initial-loader-color', primaryColor)
  </script>
</body>

</html>
