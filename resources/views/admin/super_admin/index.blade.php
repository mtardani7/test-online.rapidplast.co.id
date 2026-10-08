@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-users-cog text-red-600 mr-3"></i>Admin Management
            </h1>
            <p class="text-gray-600 mt-2">Kelola administrator sistem test online</p>
        </div>
        <a href="{{ route('admin-users.create') }}" 
           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg shadow-md hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:-translate-y-1">
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
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-red-600"></i>Daftar Administrator
                </h2>
                <span class="mt-2 md:mt-0 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <i class="fas fa-users mr-1"></i>
                    {{ $admins->count() }} Admin
                </span>
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
            <table class="min-w-full divide-y divide-gray-200">
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
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $admin->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $admin->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin-users.edit', $admin->id) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                
                                <button onclick="showDeleteModal('{{ $admin->id }}', '{{ $admin->name }}')"
                                        class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full fade-in">
        <div class="modal-header-gradient rounded-t-xl px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Hapus
                </h3>
                <button onclick="closeDeleteModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus admin berikut?</p>
            
            <div id="adminInfo" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            </div>
            
            <p class="text-sm text-red-600 mb-6">
                <i class="fas fa-info-circle mr-1"></i>
                Data yang dihapus tidak dapat dikembalikan.
            </p>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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

function showDeleteModal(id, name) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const adminInfo = document.getElementById('adminInfo');
    
    form.action = `/admin-users/${id}`;
    adminInfo.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-r from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-bold text-xl">
                ${name.charAt(0).toUpperCase()}
            </div>
            <div class="ml-4">
                <h4 class="font-bold text-gray-900">${name}</h4>
                <p class="text-sm text-gray-600">ID: ${id}</p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
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
</script>
@endsection