<body onLoad="currentURL()">
    <div id="copy-button"></div>
    <script>
    // SET data-clipboard-text TO CURRENT URL ON PAGE LOAD
    function currentURL() {
        const URL = window.location.href;
        document.getElementById('copy-button').innerHTML =
            '<a onClick="copiedToClipboard()" class="btn btn-outline-secondary btn-lg btnClipboardJS" data-clipboard-text="' +
            URL + '" id="alertCard"><i class="fa fa-link" aria-hidden="true"></i> Copier le lien</a>'
    }

    // AESTHETIC: CHANGE INNER HTML
    function copiedToClipboard() {
        document.getElementById('alertCard').innerHTML = '<i class="fa fa-check" aria-hidden="true"></i> URL copiée !';

    }
    </script>


    <!-- Clipboard.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
    <script>
    new ClipboardJS('.btnClipboardJS');
    </script>

</body>