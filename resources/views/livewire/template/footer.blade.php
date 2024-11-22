<div>
    <style>
        #footer-paragraph.collapsed p {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 100%;
            /* Adjust based on your layout */
        }

        #footer-paragraph.expanded p {
            white-space: normal;
            display: block;
        }

        #toggle-button {
            background-color: #ddd;
            border: none;
            color: black;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 16px;
        }
    </style>
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="card">
            <div class="card-body">
                <!-- <div class="d-sm-flex justify-content-center justify-content-sm-between"> -->
                <div class="col-lg-12 mb-3">
                    <h3>DEPOT DOCUMENT MANAGEMENT SYSTEM</h3>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div id="footer-paragraph" class="collapsed">
                            <p>
                                The DEPOT Document Management System is developed to assist the City Equipment Depot Office in managing requests such as vehicle repair and aircon cleaning/repair. Additionally, this system helps the office to track outgoing documents.
                            </p>
                            <button id="toggle-button">Show more...</button>
                        </div>
                        <br>
                        <br>
                        <p>
                            <span class="fw-bold">Developed by:</span> CMISID TEAM / PM: Christine B. Daguplo / DEV: Rustom C. Abella
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p>If you have issues encountered and inquiries:</p>
                        <p><a href="https://services.cagayandeoro.gov.ph/helpdesk/" target="_blank" class="link-info link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">CMISID Helpdesk</a></p>
                    </div>
                    <div class="col-md-3">
                        <div class="row mb-3">
                            <div class="col-12 col-lg-auto d-flex align-items-center">
                                <img src="{{ asset('assets/images/cdofull.png') }}" class="img-fluid mb-2 mb-lg-0" alt="cdo-full" width="150px">
                            </div>
                            <div class="col-12 col-lg d-flex justify-content-lg-end align-items-center">
                                <a class="btn btn-warning btn-rounded btn-fw" href="https://cagayandeoro.gov.ph/" target="_blank" role="button">Visit Official Website</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-auto d-flex align-items-center">
                                <img src="{{ asset('assets/images/risev2.png') }}" class="img-fluid mb-2 mb-lg-0" alt="cdo-full" width="150px">
                            </div>
                            <div class="col-12 col-lg d-flex justify-content-lg-end align-items-center">
                                <a class="btn btn-info btn-rounded btn-fw" href="https://cagayandeoro.gov.ph/index.php/news/the-city-mayor/rise1.html" target="_blank" role="button">Learn RISE Platform</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 text-center">
                        <img src="{{ asset('assets/images/ict.png') }}" class="img-fluid mb-2" alt="cmisid-logo" width="50px;">
                        <p>
                            Powered by: City Management Information Systems and Innovation Department
                            <br>
                            v1.0
                        </p>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </footer>
</div>

@script
<script>
    document.getElementById('toggle-button').addEventListener('click', function() {
        const paragraph = document.getElementById('footer-paragraph');
        const button = this;

        if (paragraph.classList.contains('collapsed')) {
            paragraph.classList.remove('collapsed');
            paragraph.classList.add('expanded');
            button.textContent = 'Show less...';
        } else {
            paragraph.classList.remove('expanded');
            paragraph.classList.add('collapsed');
            button.textContent = 'Show more...';
        }
    });
</script>
@endscript