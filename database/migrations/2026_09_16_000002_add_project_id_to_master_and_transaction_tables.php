<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Master Periods
        if (!Schema::hasColumn('master_periods', 'project_id')) {
            Schema::table('master_periods', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            });
        }

        // 2. Master Income Types
        Schema::table('master_income_types', function (Blueprint $table) {
            if (!Schema::hasColumn('master_income_types', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            }
            $table->index('user_id');
            $table->dropUnique(['user_id', 'slug']);
            $table->unique(['project_id', 'slug']);
        });

        // 3. Master Outcome Categories
        Schema::table('master_outcome_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('master_outcome_categories', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            }
            $table->index('user_id');
            $table->dropUnique(['user_id', 'slug']);
            $table->unique(['project_id', 'slug']);
        });

        // 4. Master Outcome Types
        Schema::table('master_outcome_types', function (Blueprint $table) {
            if (!Schema::hasColumn('master_outcome_types', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            }
            $table->index('user_id');
            $table->dropUnique(['user_id', 'slug']);
            $table->unique(['project_id', 'slug']);
        });

        // 5. Master Payments
        Schema::table('master_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('master_payments', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            }
            $table->index('user_id');
            $table->dropUnique(['user_id', 'slug']);
            $table->unique(['project_id', 'slug']);
        });

        // 6. Master Outcome Detail Tags
        Schema::table('master_outcome_detail_tags', function (Blueprint $table) {
            if (!Schema::hasColumn('master_outcome_detail_tags', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            }
            $table->index('user_id');
            $table->dropUnique(['user_id', 'slug']);
            $table->unique(['project_id', 'slug']);
        });

        // 7. Incomes
        if (!Schema::hasColumn('incomes', 'project_id')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            });
        }

        // 8. Outcomes
        if (!Schema::hasColumn('outcomes', 'project_id')) {
            Schema::table('outcomes', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['outcomes', 'incomes', 'master_periods'] as $tableName) {
            if (Schema::hasColumn($tableName, 'project_id')) {
                $this->dropForeignIfExists($tableName, $tableName . '_project_id_foreign');
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('project_id');
                });
            }
        }

        foreach ([
            'master_outcome_detail_tags',
            'master_payments',
            'master_outcome_types',
            'master_outcome_categories',
            'master_income_types',
        ] as $tableName) {
            if (!Schema::hasColumn($tableName, 'project_id')) {
                continue;
            }

            $this->dropForeignIfExists($tableName, $tableName . '_project_id_foreign');
            $this->dropIndexIfExists($tableName, $tableName . '_project_id_slug_unique');

            DB::statement("
                UPDATE `{$tableName}` t
                JOIN (
                    SELECT MIN(id) AS keep_id, user_id, slug
                    FROM `{$tableName}`
                    GROUP BY user_id, slug
                    HAVING COUNT(*) > 1
                ) d ON t.user_id = d.user_id AND t.slug = d.slug AND t.id <> d.keep_id
                SET t.slug = CONCAT(t.slug, '-', t.id)
            ");

            if (!$this->indexExists($tableName, $tableName . '_user_id_slug_unique')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unique(['user_id', 'slug']);
                });
            }

            $this->dropIndexIfExists($tableName, $tableName . '_user_id_index');

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('project_id');
            });
        }
    }

    private function indexExists(string $tableName, string $indexName): bool
    {
        return collect(Schema::getIndexes($tableName))->contains('name', $indexName);
    }

    private function dropIndexIfExists(string $tableName, string $indexName): void
    {
        if ($this->indexExists($tableName, $indexName)) {
            Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }

    private function dropForeignIfExists(string $tableName, string $foreignName): void
    {
        $exists = collect(Schema::getForeignKeys($tableName))->contains('name', $foreignName);

        if ($exists) {
            Schema::table($tableName, function (Blueprint $table) use ($foreignName) {
                $table->dropForeign($foreignName);
            });
        }
    }
};
