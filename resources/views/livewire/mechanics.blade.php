<div>
    <div class="card mb-2">
        <div class="card-body">
            <div class="col-sm-12 col-md-4 col-lg-2 pb-3">
                <input
                    type="text"
                    style="background-color: #fff;
                    border: 1px solid #d2d6dc;
                    border-radius: 5px;
                    font-size: 14px;
                    line-height: 1.45;
                    outline: none;
                    padding: 10px 13px;"
                    placeholder="Search..."
                    wire:model.live="search" />
            </div>
            <div class="row g-2">
                @forelse($mechanics as $item)
                <div class="col-xl-4 stretch-card">
                    <div class="card profile-card" style="background-color: #314e4f; border-radius: 5px;">
                        <div class="card-body">
                            <div class="row align-items-center h-100">
                                <div class="col-md-4">
                                    <figure class="avatar mx-auto mb-4 mb-md-0">
                                        <div class="profile-picture bg-color-{{ $item->id % 5 + 1 }}">
                                            {{ strtoupper(substr($item->name, 0, 1)) }}
                                        </div>
                                    </figure>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="text-white text-center text-md-left">{{ $item->name }}</h5>
                                    <p class="text-white text-center text-md-left"><span class="badge {{ $item->status == 'Active' ? 'badge-success text-dark' : 'badge-danger text-light' }}">{{ $item->status }}</span></p>
                                    <div class="d-flex align-items-center justify-content-between info pt-2">
                                        <div>
                                            <p class="text-white font-weight-bold">Pending</p>
                                            <p class="text-white font-weight-bold">Completed</p>
                                        </div>
                                        <div>
                                            <p class="text-white">16 Sep 2019</p>
                                            <p class="text-white">Netherlands</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center">No record.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>