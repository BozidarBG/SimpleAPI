<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
//use DB;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    /*public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $tags=['Angular', 'Bootstrap', 'HTML & CSS', 'C#', 'Java','PHP', 'Laravel', 'JavaScript',
            'React.JS', 'Foundation', 'GraphQL', 'Vue.JS', 'Spring', 'QA','REST', 'Ruby', 'Ruby On Rails', 'Django', 'Python', 'C++', 'Swift', 'React Native'];
        foreach($tags as $tag){
            $newTag= new \App\Tag();
            $newTag->title=$tag;
            $newTag->save();
        }

        $users=[
            'Nikola Jokic' =>'jokic@gmail.com',
            'James Harden'=>'harden@gmail.com',
            'Ricky Rubio'=>'rubio@gmail.com',
            'JaVale McGee'=>'mcgee@gmail.com',
            'Luka Dončić'=>'doncic@gmail.com'
        ];

        foreach($users as $user=>$email){
            $newUser= new \App\User();
            $newUser->name=$user;
            $newUser->email=$email;
            $newUser->password=Hash::make('111111');
            $newUser->save();
        }

    }*/

    public function run(){
//        factory(\App\Article::class, 100)->create();
//        for($i=0; $i<200; $i++){
//            DB::table('article_tag')->insert([
//                'article_id' => random_int(1,100),
//                'tag_id'=>random_int(1,25),
//            ]);
//        }
        factory(\App\Comment::class, 200)->create();
    }
}
