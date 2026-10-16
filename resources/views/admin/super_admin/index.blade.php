@extends('layouts.sidebar')

@section('content')
<div class="mx-auto max-w-7xl space-y-6 px-3 py-5 sm:px-6 sm:py-7 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                <i class="fas fa-users-cog text-red-600 mr-3"></i>Manajemen Admin
            </h1>
            <p class="text-gray-600 mt-2">Tambah, cari, ubah, atau hapus akun administrator.</p>
        </div>
        <a href="{{ route('admin-users.create') }}" 
           class="inline-flex min-h-11 w-full items-center justify-center px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl shadow-sm hover:from-red-700 hover:to-red-800 transition sm:w-auto">
            <i class="fas fa-plus mr-2"></i>Tambah Admin Baru
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                @if(session('generated_password'))
                <div class="mt-3">
                    <p class="text-sm text-green-700 mb-1">Password yang dibuat:</p>
                    <div class="flex items-center space-x-2">
                        <div class="password-display px-4 py-2 rounded-lg flex-1">
                            <code class="text-green-800 font-mono text-lg">{{ session('generated_password') }}</code>
                        </div>
                        <button onclick="copyToClipboard('{{ session('generated_password') }}', 'success-password')" 
                                class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="border-b border-gray-200 bg-gradient-to-r from-rose-50 to-white px-4 py-4 sm:px-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-red-600"></i>Daftar Administrator
                </h2>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <label for="adminSearch" class="sr-only">Cari nama atau email admin</label>
                    <input id="adminSearch" type="search" placeholder="Cari nama atau email admin..." class="min-h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100 sm:w-64">
                    <span class="inline-flex items-center justify-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    <span id="adminCountValue">{{ $admins->count() }} Admin</span>
                    </span>
                </div>
            </div>
        </div>

        @if($admins->isEmpty())
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-users text-gray-300 text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada admin terdaftar</h3>
            <p class="text-gray-500 mb-6">Mulai dengan menambahkan admin baru</p>
            <a href="{{ route('admin-users.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg shadow-md hover:from-red-700 hover:to-red-800 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Admin Pertama
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table id="adminsTable" class="min-w-[760px] divide-y divide-gray-200 lg:min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($admins as $index => $admin)
                    <tr data-admin-row class="hover:bg-red-50 transition-colors">
                        <td data-label="#" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td data-label="Nama admin" class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Email" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $admin->email }}
                        </td>
                        <td data-label="Bergabung" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $admin->created_at->format('d M Y') }}
                        </td>
                        <td data-label="Status" class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                Aktif
                            </span>
                        </td>
                        <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin-users.edit', $admin->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                
                                <button type="button" class="admin-delete-trigger inline-flex items-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition" data-delete-url="{{ route('admin-users.destroy', $admin->id) }}" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p id="adminSearchEmpty" class="hidden px-4 py-8 text-center text-sm text-slate-500 sm:px-6">Admin tidak ditemukan. Coba kata kunci lain.</p>
        @endif
    </div>
</div>

<div id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" class="fixed inset-0 bg-slate-950/60 hidden items-end justify-center z-50 p-0 backdrop-blur-sm sm:items-center sm:p-4">
    <div class="bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto max-w-md w-full fade-in">
        <div class="modal-header-gradient rounded-t-xl px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 id="deleteModalTitle" class="text-lg font-bold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Hapus
                </h3>
                <button type="button" id="closeDeleteModalButton" onclick="closeDeleteModal()" aria-label="Tutup dialog" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus admin berikut?</p>
            
            <div id="adminInfo" class="bg-rose-50 border border-rose-200 rounded-xl p-4 mb-4 font-semibold text-slate-800" aria-live="polite"></div>
            
            <p class="text-sm text-red-600 mb-6">
                <i class="fas fa-info-circle mr-1"></i>
                Data yang dihapus tidak dapat dikembalikan.
            </p>
            
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeleteAdmin" onclick="closeDeleteModal()"
                        class="min-h-11 w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition sm:w-auto">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form id="deleteForm" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirmDeleteAdmin"
                            class="min-h-11 w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition sm:w-auto">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, elementId = null) {
    navigator.clipboard.writeText(text).then(() => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        
        Toast.fire({
            icon: 'success',
            title: 'Password berhasil disalin!'
        })
        
        if (elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.add('copy-success');
                setTimeout(() => {
                    element.classList.remove('copy-success');
                }, 500);
            }
        }
    }).catch(err => {
        Swal.fire('Error', 'Gagal menyalin password', 'error');
    });
}

function showDeleteModal(url, id, name) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const adminInfo = document.getElementById('adminInfo');
    
    form.action = url;
    adminInfo.textContent = `${name} · ID ${id}`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('cancelDeleteAdmin')?.focus();
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.querySelectorAll('.admin-delete-trigger').forEach(button => {
    button.addEventListener('click', function() {
        showDeleteModal(this.dataset.deleteUrl, this.dataset.adminId, this.dataset.adminName);
    });
});

const adminSearch = document.getElementById('adminSearch');
adminSearch?.addEventListener('input', function() {
    const term = this.value.trim().toLocaleLowerCase();
    const rows = [...document.querySelectorAll('#adminsTable tbody [data-admin-row]')];
    let visible = 0;
    rows.forEach(row => {
        const matches = row.textContent.toLocaleLowerCase().includes(term);
        row.classList.toggle('hidden', !matches);
        if (matches) visible++;
    });
    document.getElementById('adminCountValue').textContent = `${visible} Admin`;
    document.getElementById('adminSearchEmpty')?.classList.toggle('hidden', visible !== 0);
});
</script>
<style>
@media (max-width: 767px) {
    #adminsTable, #adminsTable tbody, #adminsTable tr, #adminsTable td { display: block; width: 100%; }
    #adminsTable { min-width: 0; }
    #adminsTable thead { display: none; }
    #adminsTable tbody tr[data-admin-row] { margin: .75rem; width: calc(100% - 1.5rem); overflow: hidden; border: 1px solid #e2e8f0; border-radius: 1rem; background: #fff; box-shadow: 0 1px 2px rgb(15 23 42 / .05); }
    #adminsTable tbody tr[data-admin-row] td { display: grid; grid-template-columns: minmax(6rem, .35fr) minmax(0, 1fr); align-items: start; gap: .75rem; padding: .8rem 1rem; border-bottom: 1px solid #f1f5f9; overflow-wrap: anywhere; }
    #adminsTable tbody tr[data-admin-row] td::before { content: attr(data-label); color: #64748b; font-size: .75rem; font-weight: 600; line-height: 1.25rem; }
    #adminsTable tbody tr[data-admin-row] td:last-child { border-bottom: 0; }
    #adminsTable tbody tr[data-admin-row] td[data-label="Aksi"] > div { justify-content: flex-end; }
}
</style>
@endsection
