<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>{{ config('swagger-lume.api.title') }}</title>
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700"
    rel="stylesheet">
  <script>
    class gSwagger {
        data = {};
        jsFiles = [
          "{{ config('swagger-lume.routes.assets') }}/swagger-ui-bundle.js",
          "{{ config('swagger-lume.routes.assets') }}/swagger-ui-standalone-preset.js",
        ];
        styles = [
          "{{ config('swagger-lume.routes.assets') }}/swagger-ui.css",
        ];
        icons = [
          "{{ config('swagger-lume.routes.assets') }}/favicon-32x32.png",
          "{{ config('swagger-lume.routes.assets') }}/favicon-16x16.png",
        ];

        constructor() {
          this.data.BASE_URI = location.href.replace("{{ config('swagger-lume.routes.api') }}", '');
          this.data.DIRECTORY = this.data.BASE_URI.replace(`${location.protocol}//${location.host}`, '');
        }

        loadJs() {
          const head = document.getElementsByTagName("head")[0];

          this.jsFiles.forEach(file => {
            if (file.charAt(0) == "/") {
              file = file.substring(1);
            }
            file = `${this.data.DIRECTORY}/${file}`;

            let script = document.createElement("script");
            script.src = file;

            head.append(script);
          });
        };

        loadCss() {
          const head = document.getElementsByTagName("head")[0];

          this.styles.forEach(file => {
            if (file.charAt(0) == "/") {
              file = file.substring(1);
            }
            file = `${this.data.DIRECTORY}/${file}`;

            let style = document.createElement("link");
            style.href = file;
            style.type = "text/css";
            style.rel = "stylesheet";

            head.append(style);
          });
        };

        loadFavIcon() {
          const head = document.getElementsByTagName("head")[0];

          this.icons.forEach(file => {
            let size = file.split("/").pop();
            size = size.replace(/(favicon-|.png)/g, "");

            if (file.charAt(0) == "/") {
              file = file.substring(1);
            }
            file = `${this.data.DIRECTORY}/${file}`;

            let link = document.createElement("link");
            link.href = file;
            link.type = "image/png";
            link.rel = "icon";
            link.sizes = size;

            head.append(link);
          });
        };
      };

      const prepare = new gSwagger();

      document.addEventListener("DOMContentLoaded", function () {
        prepare.loadJs();
        prepare.loadCss();
        prepare.loadFavIcon();
      });
  </script>
  <style>
    html {
      box-sizing: border-box;
      overflow: -moz-scrollbars-vertical;
      overflow-y: scroll;
    }

    *,
    *:before,
    *:after {
      box-sizing: inherit;
    }

    body {
      margin: 0;
      background: #fafafa;
    }
  </style>
</head>

<body>

  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    style="position:absolute;width:0;height:0">
    <defs>
      <symbol viewBox="0 0 20 20" id="unlocked">
        <path
          d="M15.8 8H14V5.6C14 2.703 12.665 1 10 1 7.334 1 6 2.703 6 5.6V6h2v-.801C8 3.754 8.797 3 10 3c1.203 0 2 .754 2 2.199V8H4c-.553 0-1 .646-1 1.199V17c0 .549.428 1.139.951 1.307l1.197.387C5.672 18.861 6.55 19 7.1 19h5.8c.549 0 1.428-.139 1.951-.307l1.196-.387c.524-.167.953-.757.953-1.306V9.199C17 8.646 16.352 8 15.8 8z">
        </path>
      </symbol>

      <symbol viewBox="0 0 20 20" id="locked">
        <path
          d="M15.8 8H14V5.6C14 2.703 12.665 1 10 1 7.334 1 6 2.703 6 5.6V8H4c-.553 0-1 .646-1 1.199V17c0 .549.428 1.139.951 1.307l1.197.387C5.672 18.861 6.55 19 7.1 19h5.8c.549 0 1.428-.139 1.951-.307l1.196-.387c.524-.167.953-.757.953-1.306V9.199C17 8.646 16.352 8 15.8 8zM12 8H8V5.199C8 3.754 8.797 3 10 3c1.203 0 2 .754 2 2.199V8z" />
      </symbol>

      <symbol viewBox="0 0 20 20" id="close">
        <path
          d="M14.348 14.849c-.469.469-1.229.469-1.697 0L10 11.819l-2.651 3.029c-.469.469-1.229.469-1.697 0-.469-.469-.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-.469-.469-.469-1.228 0-1.697.469-.469 1.228-.469 1.697 0L10 8.183l2.651-3.031c.469-.469 1.228-.469 1.697 0 .469.469.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c.469.469.469 1.229 0 1.698z" />
      </symbol>

      <symbol viewBox="0 0 20 20" id="large-arrow">
        <path
          d="M13.25 10L6.109 2.58c-.268-.27-.268-.707 0-.979.268-.27.701-.27.969 0l7.83 7.908c.268.271.268.709 0 .979l-7.83 7.908c-.268.271-.701.27-.969 0-.268-.269-.268-.707 0-.979L13.25 10z" />
      </symbol>

      <symbol viewBox="0 0 20 20" id="large-arrow-down">
        <path
          d="M17.418 6.109c.272-.268.709-.268.979 0s.271.701 0 .969l-7.908 7.83c-.27.268-.707.268-.979 0l-7.908-7.83c-.27-.268-.27-.701 0-.969.271-.268.709-.268.979 0L10 13.25l7.418-7.141z" />
      </symbol>


      <symbol viewBox="0 0 24 24" id="jump-to">
        <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6 6 6 1.41-1.41L5.83 13H21V7z" />
      </symbol>

      <symbol viewBox="0 0 24 24" id="expand">
        <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
      </symbol>

    </defs>
  </svg>

  <div id="swagger-ui"></div>

  <script>
    window.onload = function() {
        // Build a system
        let spec = {};
        fetch(`${prepare.data.BASE_URI}{{ config('swagger-lume.routes.docs') }}`)
          .then(response => response.json())
          .then(json => {
            spec = json;

            if (spec.servers === undefined) {
              spec.servers = [
                { url: `${prepare.data.BASE_URI}` }
              ];
            } else {
              if (
                ! spec.servers.includes(prepare.data.BASE_URI) &&
                ! spec.servers.includes(`${prepare.data.BASE_URI}/`)
              ) {
                spec.servers.unshift({ url: `${prepare.data.BASE_URI}` });
              }
            }

            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',

                spec: spec,
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($additionalConfigUrl) ? '"' . $additionalConfigUrl . '"' : 'null' !!},
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                oauth2RedirectUrl: "{{ config('swagger-lume.routes.oauth2_callback') }}",

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                onComplete: () => {
                  const baseUri = location.href.replace("{{ config('swagger-lume.routes.api') }}", '');
                  const htmlString = `<a href="${baseUri}{{ config('swagger-lume.routes.docs') }}" target="_blank">${baseUri}{{ config('swagger-lume.routes.docs') }}</a>`;
                  let child = document.createElement("div");
                  child.innerHTML = htmlString.trim();

                  document.querySelector("hgroup.main").appendChild(child);
                },

                layout: "StandaloneLayout"
            })

            window.ui = ui;
          });
    }
  </script>
</body>

</html>
