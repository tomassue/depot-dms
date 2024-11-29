<div>
    <div class="card mb-2">
        <div class="card-body">

            <div class="col-md-2 my-2 d-flex flex-column align-items-start">
                <div class="form-group w-100">
                    <div wire:ignore>
                        <input class="form-control filter_date_range" placeholder="Date">
                    </div>
                </div>

                <div class="form-group w-100">
                    <div id="filter-status-select" wire:ignore></div>
                </div>
            </div>

            <div class="col-md-12 my-2 d-inline-flex align-items-center mb-5">
                <button class="btn btn-primary btn-md btn-icon-text me-2" wire:click="filter">
                    <i class="bx bx-filter-alt bx-sm"></i>
                </button>

                @can('print reports')
                <button class="btn btn-secondary btn-md btn-icon-text">
                    <i class="bx bx-printer bx-sm"></i>
                </button>
                @endcan
            </div>

            <div class="col-md-12 my-2">
                <div id="table_requests" wire:ignore></div>
            </div>

        </div>
    </div>
</div>

@script
<script>
    $(".filter_date_range").flatpickr({
        mode: "range",
        altInput: true,
        altFormat: 'M j, Y',
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            @this.set('filter_date_range', dateStr);
        }
    });

    $wire.on('set-date-and-time', (key) => {
        $(".filter_date_range")[0]._flatpickr.setDate(key[0]);
        @this.set('filter_date_range', key[0]);
    });

    $wire.on('reset-date-and-time', () => {
        $(".filter_date_range")[0]._flatpickr.clear(); // Clear the Flatpickr input without using a variable
        @this.set('filter_date_range', null);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#filter-status-select',
        options: @json($filter_status),
        maxWidth: '100%'
    });

    let filter_status_range = document.querySelector('#filter-status-select');
    filter_status_range.addEventListener('change', () => {
        let data = filter_status_range.value;
        @this.set('filter_status_range', data);
    });

    $wire.on('set-filter-status-select-select', (key) => {
        document.querySelector('#filter-status-select').setValue(key[0]);
    });

    $wire.on('reset-filter-status-select-select', (key) => {
        document.querySelector('#filter-status-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($table_requests); // Ensure that $mechanics includes 'deleted_at' field
    const table_requests = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            'Date',
            'Office/Department',
            'Category',
            'Sub-category',
            'Vehicle Type',
            'Diagnosis',
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    return gridjs.html(`
                        @can('read incoming')
                        <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" wire:click="readJobOrders('${id}')"><i class="bx bx-detail bx-sm"></i></button>
                        <button class="btn btn-success btn-sm btn-icon-text me-3" title="Edit" wire:click="readIncomingRequest('${id}')"><i class="bx bx-edit bx-sm"></i></button>
                        @endcan
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
                            item.id,
                            new Date(item.created_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }),
                            item.office,
                            '-',
                            '-',
                            '-',
                            '-'
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_requests"));

    $wire.on('refresh-table-incoming-requests', (data) => {
        table_requests.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                new Date(item.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                }),
                                '-',
                                '-',
                                '-',
                                '-',
                                '-'
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });
</script>
@endscript