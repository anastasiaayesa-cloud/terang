<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'key' => 'kepegawaian',
                'label' => 'Kepegawaian',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'dokumen-perencanaan',
                'label' => 'Dokumen Perencanaan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'perencanaan',
                'label' => 'Perencanaan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'bukti-pengeluaran',
                'label' => 'Bukti Pengeluaran',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'usulan-pembayaran',
                'label' => 'Usulan Pembayaran',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'keuangan',
                'label' => 'Keuangan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'usulan',
                'label' => 'Usulan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'persuratan',
                'label' => 'Persuratan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'usulan-pegawai',
                'label' => 'Usulan Pegawai',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'daftar-kegiatans',
                'label' => 'Daftar Kegiatan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'instansi',
                'label' => 'Instansi',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'laporan-kegiatans',
                'label' => 'Laporan Kegiatan',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'key' => 'role-management',
                'label' => 'Manajemen Role',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
        ];

        foreach ($menus as $menu) {
            foreach ($menu['actions'] as $action) {
                $permissionName = $menu['key'].'.'.$action;

                Permission::updateOrCreate(
                    ['name' => $permissionName],
                    ['name' => $permissionName]
                );
            }
        }

        $this->command->info('Menu permissions created successfully!');
        $this->command->info('Total permissions: '.Permission::count());

        // Give all permissions to Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
            $this->command->info('All permissions assigned to Super Admin role!');
        }
    }
}
