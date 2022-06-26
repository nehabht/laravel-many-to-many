<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            //nome colonna caterory_id
            $table->unsignedBigInteger('category_id')->nullable()->after('id');

            //vincolo per mettere in relazione la colonna category_id con la tabella categories e la sua colonna id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {

            //togli il vincolo
            $table->dropForeign('posts_category_id_foreign');
            //droppa
            $table->dropColumn('category_id');
        });
    }
}
