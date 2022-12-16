<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();        
        
        // create permissions
        $permissions = [
            'admin content list', 'admin content create', 'admin content edit', 'admin content delete',
            'admin answer list', 'admin answer create', 'admin answer edit', 'admin answer delete',
            'admin event list', 'admin event create', 'admin event edit', 'admin event delete',
            'admin appointment list', 'admin appointment create', 'admin appointment edit', 'admin appointment delete',
            'admin payment list', 'admin payment create', 'admin payment edit', 'admin payment delete',
            'admin permission list', 'admin permission create', 'admin permission edit', 'admin permission delete',
            'admin role list', 'admin role create', 'admin role edit', 'admin role delete',
            'admin user list', 'admin user create', 'admin user edit', 'admin user delete',

            'professional profile list', 'professional profile create', 'professional profile edit', 'professional profile delete',
            'professional content list', 'professional content create', 'professional content edit', 'professional content delete',
            'professional answer list', 'professional answer create', 'professional answer edit', 'professional answer delete',
            'professional event list', 'professional event create', 'professional event edit', 'professional event delete',
            'professional appointment list', 'professional appointment create', 'professional appointment edit', 'professional appointment delete',
            'professional payment list', 'professional payment create', 'professional payment edit', 'professional payment delete',
            'professional user list', 'professional user create', 'professional user edit', 'professional user delete',
            'professional chat list', 'professional chat create', 'professional chat edit', 'professional chat delete',
            'professional appointment content list', 'professional appointment content create', 'professional appointment content edit', 'professional appointment content delete',
            'professional appointment answer list', 'professional appointment answer create', 'professional appointment answer edit', 'professional appointment answer delete',
            'professional appointment task list', 'professional appointment task create', 'professional appointment task edit', 'professional appointment task delete',
            'professional event task list', 'professional event task create', 'professional event task edit', 'professional event task delete',
            'professional event content list', 'professional event content create', 'professional event content edit', 'professional event content delete',

            'user profile list', 'user profile create', 'user profile edit', 'user profile delete',
            'user content list', 'user content create', 'user content edit', 'user content delete',
            'user answer list', 'user answer create', 'user answer edit', 'user answer delete',
            'user event list', 'user event create', 'user event edit', 'user event delete',
            'user appointment list', 'user appointment create', 'user appointment edit', 'user appointment delete',
            'user payment list', 'user payment create', 'user payment edit', 'user payment delete',
            'user chat list', 'user chat create', 'user chat edit', 'user chat delete',
            'user appointment task list', 'user appointment task create', 'user appointment task edit', 'user appointment task delete',
            'user appointment answer list', 'user appointment answer create', 'user appointment answer edit', 'user appointment answer delete',
        ];        
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }        

        // create roles 
        $role1 = Role::create(['name' => 'user']);
        $role2 = Role::create(['name' => 'professional']);
        $role3 = Role::create(['name' => 'admin']);
        $role4 = Role::create(['name' => 'super-admin']);
        
        // assign permissions to existing roles

        // role 1
        $role1->givePermissionTo('user profile list');
        $role1->givePermissionTo('user profile create');
        $role1->givePermissionTo('user profile edit');
        $role1->givePermissionTo('user profile delete');

        $role1->givePermissionTo('user content list');
        $role1->givePermissionTo('user content create');
        $role1->givePermissionTo('user content edit');
        $role1->givePermissionTo('user content delete');

        $role1->givePermissionTo('user answer list');
        $role1->givePermissionTo('user answer create');
        $role1->givePermissionTo('user answer edit');
        $role1->givePermissionTo('user answer delete');

        $role1->givePermissionTo('user event list');
        $role1->givePermissionTo('user event create');
        $role1->givePermissionTo('user event edit');
        $role1->givePermissionTo('user event delete');

        $role1->givePermissionTo('user appointment list');
        $role1->givePermissionTo('user appointment create');
        $role1->givePermissionTo('user appointment edit');
        $role1->givePermissionTo('user appointment delete');

        $role1->givePermissionTo('user payment list');
        $role1->givePermissionTo('user payment create');
        $role1->givePermissionTo('user payment edit');
        $role1->givePermissionTo('user payment delete');

        $role1->givePermissionTo('user chat list');
        $role1->givePermissionTo('user chat create');
        $role1->givePermissionTo('user chat edit');
        $role1->givePermissionTo('user chat delete');

        $role1->givePermissionTo('user appointment task list');
        $role1->givePermissionTo('user appointment task create');
        $role1->givePermissionTo('user appointment task edit');
        $role1->givePermissionTo('user appointment task delete');


        // role 2
        $role2->givePermissionTo('user profile list');
        $role2->givePermissionTo('user profile create');
        $role2->givePermissionTo('user profile edit');
        $role2->givePermissionTo('user profile delete');

        $role2->givePermissionTo('user content list');
        $role2->givePermissionTo('user content create');
        $role2->givePermissionTo('user content edit');
        $role2->givePermissionTo('user content delete');

        $role2->givePermissionTo('user answer list');
        $role2->givePermissionTo('user answer create');
        $role2->givePermissionTo('user answer edit');
        $role2->givePermissionTo('user answer delete');

        $role2->givePermissionTo('user event list');
        $role2->givePermissionTo('user event create');
        $role2->givePermissionTo('user event edit');
        $role2->givePermissionTo('user event delete');

        $role2->givePermissionTo('user appointment list');
        $role2->givePermissionTo('user appointment create');
        $role2->givePermissionTo('user appointment edit');
        $role2->givePermissionTo('user appointment delete');

        $role2->givePermissionTo('user payment list');
        $role2->givePermissionTo('user payment create');
        $role2->givePermissionTo('user payment edit');
        $role2->givePermissionTo('user payment delete');

        $role2->givePermissionTo('user chat list');
        $role2->givePermissionTo('user chat create');
        $role2->givePermissionTo('user chat edit');
        $role2->givePermissionTo('user chat delete');

        $role2->givePermissionTo('user appointment task list');
        $role2->givePermissionTo('user appointment task create');
        $role2->givePermissionTo('user appointment task edit');
        $role2->givePermissionTo('user appointment task delete');


        $role2->givePermissionTo('professional profile list');
        $role2->givePermissionTo('professional profile create');
        $role2->givePermissionTo('professional profile edit');
        $role2->givePermissionTo('professional profile delete');

        $role2->givePermissionTo('professional content list');
        $role2->givePermissionTo('professional content create');
        $role2->givePermissionTo('professional content edit');
        $role2->givePermissionTo('professional content delete');

        $role2->givePermissionTo('professional answer list');
        $role2->givePermissionTo('professional answer create');
        $role2->givePermissionTo('professional answer edit');
        $role2->givePermissionTo('professional answer delete');

        $role2->givePermissionTo('professional event list');
        $role2->givePermissionTo('professional event create');
        $role2->givePermissionTo('professional event edit');
        $role2->givePermissionTo('professional event delete');

        $role2->givePermissionTo('professional appointment list');
        $role2->givePermissionTo('professional appointment create');
        $role2->givePermissionTo('professional appointment edit');
        $role2->givePermissionTo('professional appointment delete');

        $role2->givePermissionTo('professional payment list');
        $role2->givePermissionTo('professional payment create');
        $role2->givePermissionTo('professional payment edit');
        $role2->givePermissionTo('professional payment delete');

        $role2->givePermissionTo('professional user list');
        $role2->givePermissionTo('professional user create');
        $role2->givePermissionTo('professional user edit');
        $role2->givePermissionTo('professional user delete');

        $role2->givePermissionTo('professional chat list');
        $role2->givePermissionTo('professional chat create');
        $role2->givePermissionTo('professional chat edit');
        $role2->givePermissionTo('professional chat delete');

        $role2->givePermissionTo('professional appointment content list');
        $role2->givePermissionTo('professional appointment content create');
        $role2->givePermissionTo('professional appointment content edit');
        $role2->givePermissionTo('professional appointment content delete');

        $role2->givePermissionTo('professional appointment task list');
        $role2->givePermissionTo('professional appointment task create');
        $role2->givePermissionTo('professional appointment task edit');
        $role2->givePermissionTo('professional appointment task delete');

        $role2->givePermissionTo('professional event task list');
        $role2->givePermissionTo('professional event task create');
        $role2->givePermissionTo('professional event task edit');
        $role2->givePermissionTo('professional event task delete');

        $role2->givePermissionTo('professional event content list');
        $role2->givePermissionTo('professional event content create');
        $role2->givePermissionTo('professional event content edit');
        $role2->givePermissionTo('professional event content delete');


        
        // role 3
        foreach ($permissions as $permission) {
            $role3->givePermissionTo($permission);
        }        
        
        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@gmail.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Professional User',
            'email' => 'professional@gmail.com',
            'professional_status' => 'Approve',
        ]);
        $user->assignRole($role2);
        
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'professional_status' => 'Approve',
        ]);  
        $user->assignRole($role3);

        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'professional_status' => 'Approve',
        ]);
        $user->assignRole($role4);        
        
    }
}