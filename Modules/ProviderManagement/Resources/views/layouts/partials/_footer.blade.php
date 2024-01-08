<footer class="footer mt-auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center justify-content-md-start mb-2 mb-md-0">
                {{(business_config('footer_text','business_information'))->live_values??""}} <span class="currentYear ml-3"></span>
            </div>
        </div>
    </div>
</footer>
