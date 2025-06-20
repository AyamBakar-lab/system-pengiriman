<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pencatatan Pengiriman Barang</title>
    <meta name="description" content="Aplikasi pencatatan pengiriman barang dengan fitur lengkap untuk tracking dan manajemen data pengiriman">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🚛 Sistem Pencatatan Pengiriman Barang</h1>
        </div>
    </div>

    <div class="container">
        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <button class="nav-tab active" data-tab="form">📝 Form Pengiriman</button>
            <button class="nav-tab" data-tab="list">📋 Daftar Pengiriman</button>
        </div>

        <!-- Form Tab -->
        <div id="formTab" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <span id="formTitle">📝 Form Pencatatan Pengiriman Barang</span>
                </div>
                <div class="card-content">
                    <form id="shipmentForm" class="form">
                        <!-- Informasi Pengiriman -->
                        <div class="form-section">
                            <h3>🚛 Informasi Pengiriman</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="id_form">ID Form *</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="text" id="id_form" name="id_form" class="form-input" placeholder="AUTO-GENERATE" readonly style="background: #e3f2fd; cursor: not-allowed; font-weight: bold; color: #1976d2;">
                                        <button type="button" id="generateFormIdBtn" class="btn btn-primary" title="Generate Ulang ID Form">
                                            🔄
                                        </button>
                                    </div>
                                    <small style="color: #1976d2; font-size: 12px; font-weight: 500;">ID form akan dibuat otomatis dengan format FORM-YYYYMMDD-XXXX</small>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="no_dokumen">No. Dokumen *</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="text" id="no_dokumen" name="no_dokumen" class="form-input" placeholder="AUTO-GENERATE" readonly style="background: #f8f9fa; cursor: not-allowed;">
                                        <button type="button" id="generateDocBtn" class="btn btn-secondary" title="Generate Ulang No. Dokumen">
                                            🔄
                                        </button>
                                    </div>
                                    <small style="color: #6b7280; font-size: 12px;">No. dokumen akan dibuat otomatis dengan format DOK-YYYYMMDD-XXXX</small>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="no_polisi">No. Polisi *</label>
                                    <input type="text" id="no_polisi" name="no_polisi" class="form-input" placeholder="Contoh: B 1234 XYZ" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="no_segel">No. Segel *</label>
                                    <input type="text" id="no_segel" name="no_segel" class="form-input" placeholder="Contoh: SGL-001234" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cabang_tujuan">Cabang Tujuan *</label>
                                    <select id="cabang_tujuan" name="cabang_tujuan" class="form-select" required>
                                        <option value="">Pilih Cabang</option>
                                        <option value="jakarta">Jakarta</option>
                                        <option value="bandung">Bandung</option>
                                        <option value="surabaya">Surabaya</option>
                                        <option value="medan">Medan</option>
                                        <option value="makassar">Makassar</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Personel -->
                        <div class="form-section">
                            <h3>👥 Informasi Personel</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="nama_driver">Nama Driver *</label>
                                    <input type="text" id="nama_driver" name="nama_driver" class="form-input" placeholder="Masukkan nama driver" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="nama_co_driver">Nama Co-Driver</label>
                                    <input type="text" id="nama_co_driver" name="nama_co_driver" class="form-input" placeholder="Masukkan nama co-driver (opsional)">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="nama_staff">Nama Staff *</label>
                                    <input type="text" id="nama_staff" name="nama_staff" class="form-input" placeholder="Masukkan nama staff" required>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Barang -->
                        <div class="form-section">
                            <div class="flex justify-between align-center mb-20">
                                <h3>📦 Daftar Barang</h3>
                                <button type="button" id="addItemBtn" class="btn btn-success">
                                    ➕ Tambah Barang
                                </button>
                            </div>
                            
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>No. Dokumen</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Jumlah</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <!-- Items will be rendered here by JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="form-section">
                            <h3>📝 Catatan Tambahan</h3>
                            <div class="form-group">
                                <label class="form-label" for="catatan_pengiriman">Catatan Pengiriman</label>
                                <textarea id="catatan_pengiriman" name="catatan_pengiriman" class="form-textarea" rows="4" placeholder="Masukkan catatan atau keterangan tambahan untuk pengiriman ini..."></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-10 mt-20">
                            <button type="button" id="cancelEdit" class="btn btn-outline hidden">
                                ❌ Batal
                            </button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">
                                💾 Simpan Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Tab -->
        <div id="listTab" class="tab-content hidden">
            <div class="card">
                <div class="card-header">
                    📋 Daftar Pengiriman
                </div>
                <div class="card-content">
                    <!-- Search and Filter -->
                    <div class="search-filters">
                        <div class="form-group search-input">
                            <input type="text" id="searchInput" class="form-input" placeholder="🔍 Cari berdasarkan No. Polisi, Driver, atau Cabang...">
                        </div>
                        <div class="form-group">
                            <select id="branchFilter" class="form-select">
                                <option value="semua">Semua Cabang</option>
                                <option value="jakarta">Jakarta</option>
                                <option value="bandung">Bandung</option>
                                <option value="surabaya">Surabaya</option>
                                <option value="medan">Medan</option>
                                <option value="makassar">Makassar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="text" id="formIdSearch" class="form-input" placeholder="🆔 Cari berdasarkan ID Form..." style="background: #e3f2fd; font-weight: 500;">
                                <button type="button" id="searchFormBtn" class="btn btn-primary">
                                    🔍 Cari Form
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Shipments List -->
                    <div id="shipmentsList" class="shipments-list">
                        <div class="loading">
                            <div class="spinner"></div>
                            Memuat data pengiriman...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>