<div>
    <div class="col-md-12 mb-2 d-inline-flex align-content-center justify-content-start">
        <button type="button" class="btn btn-secondary btn-icon-text" style="transform: rotate(0);">
            <a href="{{ route('mechanics-list') }}" class="stretched-link"></a>
            <i class='bx bx-arrow-back bx-xs'></i>
        </button>
    </div>
    <div class="col-xl-12 stretch-card mb-2">
        <div class="card profile-card position-relative" style="background-color: #314e4f; border-radius: 5px;">
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-md-4">
                        <figure class="avatar mx-auto mb-4 mb-md-0">
                            <div class="profile-picture bg-color-{{ $mechanic->id % 5 + 1 }}">
                                {{ strtoupper(substr($mechanic->name, 0, 1)) }}
                            </div>
                        </figure>
                    </div>
                    <div class="col-md-8">
                        <h5 class="text-white text-center text-md-left">{{ $mechanic->name }}</h5>
                        <p class="text-white text-center text-md-left">
                            <span class="badge {{ $mechanic->status == 'Active' ? 'badge-success text-dark' : 'badge-danger text-light' }}">
                                {{ $mechanic->status }}
                            </span>
                        </p>
                        <div class="d-flex align-items-center justify-content-between info pt-2">
                            <div>
                                <p class="text-white font-weight-bold">Pending</p>
                                <p class="text-white font-weight-bold">Completed</p>
                                <p class="text-white font-weight-bold">Total</p>
                            </div>
                            <div>
                                <p class="text-white fw-bold">{{ $mechanic->pending_jobs }}</p>
                                <p class="text-white fw-bold">{{ $mechanic->completed_jobs }}</p>
                                <p class="text-white fw-bold">{{ $mechanic->total_jobs }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <div class="col-md-12 my-2 d-inline-flex align-items-center" title="Filter">
                <button class="btn btn-info btn-md btn-icon-text me-4" title="Print" wire:click="printJobOrders">
                    <i class="bx bx-printer bx-sm"></i>
                </button>
            </div>
            <div class="col-md-12 my-2">
                <div id="table_mechanic_job_orders" wire:ignore></div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('generate-pdf', (url) => {
        window.open(event.detail.url, '_blank'); // Open new tab
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($mechanic_jobs);
    const table_mechanic_job_orders = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Job Order No.",
            "Category",
            "Sub-category",
            {
                name: "Status",
                formatter: (cell, row) => {
                    const status = row.cells[4].data;
                    const getBadgeClass = (status) => {
                        if (status === 'Pending') return 'text-bg-danger';
                        if (status === 'Completed') return 'text-bg-success';
                        if (status === 'Referred to') return 'text-bg-info';
                    };

                    return gridjs.html(`
                                <span class="badge rounded-pill ${getBadgeClass(status)}">
                                ${row.cells[4].data}
                                </span>
                            `);
                }
            },
            "Type of Repair"
        ],
        search: true,
        pagination: {
            limit: 10
        },
        sort: true,
        autoWidth: true,
        data: () => {
            return new Promise(resolve => {
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id,
                            item.id,
                            item.category.name,
                            item.sub_category_names,
                            item.status.name,
                            item.type_of_repair.name
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_mechanic_job_orders"));

    $wire.on('refresh-table_mechanic_job_orders', (data) => {
        table_mechanic_job_orders.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.id,
                                item.ref_category_id,
                                item.ref_sub_category_id,
                                item.ref_status_id,
                                item.ref_type_of_repair_id
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });
</script>
@endscript