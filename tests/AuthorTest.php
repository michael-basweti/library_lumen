<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Book;
use App\Author;
use App\User;
class AuthorTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @return void
     */
    public function testCreateAuthor()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = [
            'name'=>'Mike',
            'email'=>'baswetima@gmail.com',
            'dob'=>2006];
        $response = $this->post('/api/v1/authors', $author);
        $response->assertResponseStatus(201);
    }

    public function testCreateAuthorWithoutAuth()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);

        $author = [
            'name'=>'Mike',
            'email'=>'baswetima@gmail.com',
            'dob'=>2006];
        $response = $this->post('/api/v1/authors', $author);
        $response->assertResponseStatus(401);
    }

    public function testCreateAuthorTwice()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author1 = factory(Author::class)->create();
        $author = [
            'name'=>'Mike',
            'email'=>$author1->email,
            'dob'=>2006];
        $response = $this->post('/api/v1/authors', $author);
        $response->assertResponseStatus(422);
    }

    public function testuserCanViewAuthors()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/authors");
        $response->assertResponseStatus(200);
        $response->seeJson(['name' => $author->name]);
    }
    public function testuserCanSearchAuthors()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/authors?name={$author->name}");
        $response->assertResponseStatus(200);
        $response->seeJson(['name' => $author->name]);
    }
    public function testuserCanLimitAndOffsetAuthors()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $author1 = factory(Author::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/authors?limit=1&offset=2");
        $response->assertResponseStatus(200);
    }
    public function testUserCannotViewAuthorsWithoutAuth()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $response = $this->get("/api/v1/authors");
        $response->assertResponseStatus(401);
    }
    public function testUserCannotViewOneAuthorWithoutAuth()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $author = factory(Author::class)->create();
        $response = $this->get("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(401);
    }
    public function testUserCanViewOneAuthor()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $author = factory(Author::class)->create();
        $response = $this->get("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(200);
    }
    public function testUserCannotViewNonExistingAuthor()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $author = factory(Author::class)->create();
        $response = $this->get("/api/v1/authors/201");
        $response->assertResponseStatus(404);
    }

    public function testUserCanUpdateAuthorWithAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/authors/{$author->id}", ["name" => "New Name"]);
        $updatedBook = $this->get("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(200);
        $updatedBook->seeJson(["name" => "New Name"]);
    }
    public function testUserCannotUpdateAuthorWithoutAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $response = $this->put("/api/v1/authors/{$author->id}", ["name" => "New Name"]);
        $updatedBook = $this->get("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(401);
    }
    public function testUserCannotUpdateNonExistingBook()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/authors/304", ["name" => "New Name"]);
        $updatedBook = $this->get("/api/v1/authors/304");
        $response->assertResponseStatus(404);
    }

    public function testUserCanDeleteAuthorWithAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $response = $this->delete("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(204);
    }
    public function testUserCanNotDeleteAuthorWithoutAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author= factory(Author::class)->create();
        $response = $this->delete("/api/v1/authors/{$author->id}");
        $response->assertResponseStatus(401);
    }

    public function testDeleteAuthorNotExist()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $response = $this->delete("/api/v1/authors/224");
        $response->assertResponseStatus(404);
    }

}
