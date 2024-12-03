<div>
    @include('livewire.template.loading-spinner')

    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card flex-column">
            <div class="row">
                <div class="col-md-3 my-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left">Total Job Orders</p>
                                    <h1 class="mb-0">{{ $total }}</h1>
                                </div>
                                <i class="bx bx-file bx-lg text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 my-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left">Pending Job Orders</p>
                                    <h1 class="mb-0">{{ $pending }}</h1>
                                </div>
                                <i class="bx bx-loader-circle bx-lg text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 my-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left">Accomplished Job Orders</p>
                                    <h1 class="mb-0">{{ $done }}</h1>
                                </div>
                                <i class="bx bx-badge-check bx-lg text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-3 my-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left">Total Expenses</p>
                                    <h1 class="mb-0">8742</h1>
                                </div>
                                <i class="bx bx-loader-circle bx-lg text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>