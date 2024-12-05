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

    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card flex-column">
            <div class="card">
                <div class="card-body">
                    <div id="table_pending_job_orders" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.modals.incoming.jobOrderModal');
</div>

@script
<script>
    $wire.on('showJobOrderModal', () => {
        $('#jobOrderModal').modal('show');
    });

    $wire.on('hideJobOrderModal', () => {
        $('#jobOrderModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($table_pending_job_orders); // Ensure that $mechanics includes 'deleted_at' field
    const table_pending_job_orders = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Job Order No.",
            "Category",
            "Sub-category",
            "Status",
            "Date & Time (Out)",
            "Total Repair Time",
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    return gridjs.html(`
                        @can('read incoming')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" title="Edit" style="display: ${row.cells[4].data === 'Pending' ? '' : 'none'}" wire:click="readJobOrder('${id}')">
                            <i class="bx bx-edit bx-sm"></i>
                        </button>
                        <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" style="display: ${row.cells[4].data === 'Pending' ? 'none' : ''}" wire:click="readJobOrdersDetails('${id}')">
                            <i class="bx bx-detail bx-sm"></i>
                        </button>
                        @endcan
                        <button class="btn btn-white btn-sm btn-icon-text me-3" title="Print" wire:click="assignSignatory('${id}')">
                            <i class="bx bx-printer bx-sm"></i>
                        </button>
                    `);
                }
            }
        ],
        search: true,
        pagination: {
            limit: 10
        },
        sort: true,
        data: () => {
            return new Promise(resolve => {
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id, // Primary Key
                            item.id,
                            item.category.name,
                            item.sub_category.name,
                            item.status.name,
                            item.date_and_time_out ?
                            new Date(item.date_and_time_out).toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }) :
                            '-',
                            item.total_repair_time ? item.total_repair_time : '-'
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_pending_job_orders"));

    $wire.on('refresh-table-incoming-requests', (data) => {
        table_pending_job_orders.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id, // Primary Key
                                item.id,
                                item.category.name,
                                item.sub_category.name,
                                item.status.name,
                                item.date_and_time_out ?
                                new Date(item.date_and_time_out).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                }) :
                                '-',
                                item.total_repair_time ? item.total_repair_time : '-'
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });
</script>
@endscript