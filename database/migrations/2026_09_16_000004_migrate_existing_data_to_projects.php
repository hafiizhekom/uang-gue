<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $users = User::all();

            foreach ($users as $user) {
                // Cek apakah user sudah punya project
                $project = $user->projects()->first();

                if (!$project) {
                    $project = Project::create([
                        'name'        => $user->name . "'s Workspace",
                        'description' => 'Default personal workspace for ' . $user->name,
                        'created_by'  => $user->id,
                    ]);

                    $project->users()->attach($user->id, ['role' => 'owner']);
                }

                // Associate user's master records with this project
                DB::table('master_periods')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('master_income_types')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('master_outcome_categories')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('master_outcome_types')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('master_payments')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('master_outcome_detail_tags')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);

                // Associate user's transactions with this project
                DB::table('incomes')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);
                DB::table('outcomes')->where('user_id', $user->id)->whereNull('project_id')->update(['project_id' => $project->id]);

                // Associate activity logs
                DB::table('activity_log')
                    ->where('causer_type', User::class)
                    ->where('causer_id', $user->id)
                    ->whereNull('project_id')
                    ->update(['project_id' => $project->id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('master_periods')->update(['project_id' => null]);
        DB::table('master_income_types')->update(['project_id' => null]);
        DB::table('master_outcome_categories')->update(['project_id' => null]);
        DB::table('master_outcome_types')->update(['project_id' => null]);
        DB::table('master_payments')->update(['project_id' => null]);
        DB::table('master_outcome_detail_tags')->update(['project_id' => null]);
        DB::table('incomes')->update(['project_id' => null]);
        DB::table('outcomes')->update(['project_id' => null]);
        DB::table('activity_log')->update(['project_id' => null]);
        DB::table('project_user')->delete();
        DB::table('projects')->delete();
    }
};
