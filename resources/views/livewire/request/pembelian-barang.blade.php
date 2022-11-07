<div>
    <div class="section-header tw-rounded-lg tw-text-black tw-shadow-md">
        <h4 class="tw-text-lg">Pembelian Barang</h4>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-3">
                <div class="card tw-rounded-md tw-shadow-md">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="datetime-local" class="form-control tw-rounded-lg" wire:model='tanggal'
                                    id="tanggal">
                            </div>
                            <div class="form-group">
                                <label for="id_user_request">Nama Request</label>
                                <div wire:ignore>
                                    <select class="form-control tw-rounded-lg" wire:model='id_user_request'
                                        id="id_user_request">
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input type="text" class="form-control tw-rounded-lg" wire:model='nama_barang'
                                            id="nama_barang">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="qty">Qty</label>
                                        <input type="number" class="form-control tw-rounded-lg" min="1" wire:model='qty'
                                            id="qty">
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" class="form-control tw-rounded-lg" min="0"
                                            wire:model='harga' id="harga">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="number" class="form-control tw-rounded-lg" min="0" wire:model='total'
                                    id="total">
                            </div>
                            <div class="form-group">
                                <label for="link_pembelian">Link Pembelian</label>
                                <input type="text" class="form-control tw-rounded-lg" min="0"
                                    wire:model='link_pembelian' id="link_pembelian">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control tw-rounded-lg" wire:model='keterangan' id="keterangan"
                                    style="height: 100px"></textarea>
                            </div>
                            <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                                class="btn btn-outline-success form-control">Save Data</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card card-primary tw-rounded-md tw-shadow-md">
                    <div class="card-body px-0">
                        <div class="row mb-3 px-4">
                            <div class="col-4 col-lg-2 tw-flex">
                                <select class="form-control tw-rounded-lg" wire:model='lengthData'>
                                    <option value="0" selected>All</option>
                                    <option value="1" selected>1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                            <div class="col-8 col-lg-4 ml-auto tw-flex">
                                <span class="mt-2 text-dark mr-1 tw-hidden lg:tw-block">Search:</span>
                                <input wire:model="searchTerm" type="search" class="form-control tw-rounded-lg ml-auto"
                                    placeholder="Search here.." wire:model='searchTerm'>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="tw-table-fixed tw-w-full tw-text-black tw-text-md mt-3 tw-border-collapse tw-border">
                                <thead>
                                    <tr class="tw-border-b tw-text-xs text-uppercase text-center">
                                        <th class="p-3" width="30%">Nama Barang</th>
                                        <th class="p-3">Harga</th>
                                        <th class="p-3">Total Harga</th>
                                        <th class="p-3">Keterangan</th>
                                        <th class="p-3 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $row)
                                    <tr
                                        class="tw-bg-white tw-border tw-text-uppercase tw-border-gray-200 hover:tw-bg-gray-50">
                                        <td class="p-3">
                                            <span
                                                class="tw-text-gray-500">{{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                                - {{ $row->user_request }}</span> <br>
                                            <a href="{{ $row->link_pembelian }}"
                                                target="_BLANK"
                                                class="tw-font-bold">
                                                {{ $row->nama_barang }}
                                            </a>
                                            <span class="tw-font-bold">
                                                @ {{ $row->qty }}pcs</span> <br><br>
                                            @if ($row->status == "Menunggu Persetujuan")
                                            <span
                                                class="tw-bg-yellow-200 tw-px-2 py-1 tw-text-xs tw-rounded-md tw-text-yellow-900 tw-font-bold">
                                                <i class="fas fa-exclamation-circle"></i> Menunggu Persetujuan
                                            </span>
                                            @else
                                            <span
                                                class="tw-bg-green-200 tw-px-2 py-1 tw-text-xs tw-rounded-md tw-text-green-900 tw-font-bold">
                                                <i class="fas fa-badge-check"></i> Disetujui oleh
                                                {{ $row->user_accept }}
                                            </span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            Rp{{ number_format($row->harga,0,",",".") }}
                                        </td>
                                        <td class="p-3 text-center">
                                            Rp{{ number_format($row->total,0,",",".") }}
                                        </td>
                                        <td class="p-3 text-center">
                                            {{ $row->keterangan }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @if ($row->status == "Menunggu Persetujuan")
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#ubahDataModal" wire:click="edit({{ $row->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger"
                                                wire:click.prevent="deleteConfirm({{ $row->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @else
                                            @if ($row->kode_resi)
                                            <a href="#" data-toggle="modal" data-target="#viewTracking" wire:click.prevent="cekResi('{{ $row->kode_resi }}', '{{ $row->nama_kurir }}')">View
                                                Tracking</a>
                                            @else
                                            <span class="text-warning tw-text-xs">Menunggu kode resi..</span>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td class="p-3" colspan="2">
                                            No data available in table
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive p-3">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button
            class="tw-fixed tw-right-[30px] tw-bottom-[50px] tw-w-14 tw-h-14 tw-shadow-2xl tw-rounded-full tw-bg-slate-600 tw-z-40 text-white hover:tw-bg-slate-900 hover:tw-border-slate-600"
            data-toggle="modal" data-target="#tambahDataModal">
            <i class="far fa-plus"></i>
        </button>
    </div>

    <div class="modal fade" wire:ignore.self id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime-local" class="form-control tw-rounded-lg" wire:model='tanggal'
                                id="tanggal">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="id_user_request">Nama Request</label>
                                    <div wire:ignore>
                                        <select class="form-control tw-rounded-lg" wire:model='id_user_request'
                                            id="id_user_request">
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg"
                                                wire:model='nama_barang' id="nama_barang">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="1" wire:model='qty'
                                        id="qty">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="0" wire:model='harga'
                                        id="harga">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="0" wire:model='total'
                                        id="total">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="link_pembelian">Link Pembelian</label>
                            <input type="text" class="form-control tw-rounded-lg" min="0" wire:model='link_pembelian'
                                id="link_pembelian">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control tw-rounded-lg" wire:model='keterangan' id="keterangan"
                                style="height: 100px"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                            class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="ubahDataModal" tabindex="-1" aria-labelledby="ubahDataModalLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahDataModalLabel">Edit Data</h5>
                    <button type="button" wire:click.prevent='cancel()' class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" wire:model='dataId'>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="id_user_request">Nama Request</label>
                                    <div wire:ignore>
                                        <select class="form-control tw-rounded-lg" wire:model='id_user_request'
                                            id="id_user_request">
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control tw-rounded-lg"
                                                wire:model='nama_barang' id="nama_barang">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="1" wire:model='qty'
                                        id="qty">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="0" wire:model='harga'
                                        id="harga">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" class="form-control tw-rounded-lg" min="0" wire:model='total'
                                        id="total">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="link_pembelian">Link Pembelian</label>
                            <input type="text" class="form-control tw-rounded-lg" min="0" wire:model='link_pembelian'
                                id="link_pembelian">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control tw-rounded-lg" wire:model='keterangan' id="keterangan"
                                style="height: 100px"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent='cancel()' class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button wire:click.prevent="update()" wire:loading.attr="disabled" type="button"
                            class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="viewTracking" tabindex="-1" aria-labelledby="viewTrackingLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content tw-rounded-tr-xl tw-rounded-tl-xl">
                <div class="modal-header tw-bg-blue-400 tw-rounded-tr-xl tw-rounded-tl-xl ">
                    <h5 class="modal-title tw-text-white mb-3" id="viewTrackingLabel"><i
                            class="fas fa-badge-check tw-text-lg mr-2"></i> {{ $status_order }} - {{ $courier }}</h5>
                    <button type="button" wire:click.prevent='cancel()' class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-2">
                    <p>Nomor Resi</p>
                    <p class="tw-text-black tw-font-bold tw-tracking-widest tw-select-all">{{ $awb }}</p>
                    <div class="tw-flex tw-justify-between mt-3">
                        <div>
                            <p class="tw-font-semibold tw-text-black">{{ $shipper }}</p>
                        </div>
                        <div>
                        </div>
                        <div>
                            <p class="tw-font-semibold tw-text-black">{{ $receiver }}</p>
                        </div>
                    </div>
                    <div class="tw-flex tw-justify-between mt-3">
                        <div>
                            <p>{{ $origin }}</p>
                        </div>
                        <div>
                        </div>
                        <div>
                            <p>{{ $destination }}</p>
                        </div>
                    </div>
                    <div class="tw-overflow-y-auto tw-h-96 no-scrollbar mt-5">
                        <div class="activities px-4 ">
                            @foreach ((array)$histories as $history)
                                <div class="activity">
                                    <div class="activity-icon tw-bg-blue-400 text-white shadow-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span class="text-job text-primary">{{ \Carbon\Carbon::parse($history['date'])->diffForHumans() }}</span>
                                            {{-- <span class="bullet"></span> --}}
                                            {{-- <a class="text-job" href="#">PICKREQ</a> --}}
                                        </div>
                                        <p>{{ $history['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function () {
        $('#id_user_request').select2();
        $('#id_user_request').on('change', function (e) {
            var data = $('#id_user_request').select2("val");
            @this.set('id_user_request', data);
        });
    });

</script>

@endpush
