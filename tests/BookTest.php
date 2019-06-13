<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Book;
use App\Author;
use App\User;
class BooksTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @return void
     */
    public function testCreateBook()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $book = [
            'title'=>'A book I wont write',
            'description'=>'The book I love',
            'year_of_publication'=>2006,
            'publisher'=>'longhorn',
            'author_id' =>$author->id];
        $response = $this->post('/api/v1/books', $book);
        $response->assertResponseStatus(201);
    }
    public function testCreateBookWithoutAuth()
    {
        $author = factory(Author::class)->create();
        $book = [
            'title'=>'A book I wont write',
            'description'=>'The book I love',
            'year_of_publication'=>2006,
            'publisher'=>'longhorn',
            'author_id' =>$author->id];
        $response = $this->post('/api/v1/books', $book);
        $response->assertResponseStatus(401);
    }

    public function testCreateBookTwice()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $author = factory(Author::class)->create();
        $firstBook = factory(Book::class)->create();
        $secondBook = [
            'title'=>$firstBook->title,
            'description'=>'The book I love',
            'year_of_publication'=>2006,
            'publisher'=>'longhorn',
            'author_id' =>$author->id];
        $response = $this->post('/api/v1/books', $secondBook);
        $response->assertResponseStatus(422);
    }

    public function testuserCanViewBooks()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books");
        $response->assertResponseStatus(200);
        $response->seeJson(['title' => $book->title]);
        $response->seeJson(['id' => $book1->id]);
    }
    public function testSearchBooksByAuth0r()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books?author={$author->name}");
        $response->assertResponseStatus(200);
        $response->seeJson(['title' => $book->title]);
        $response->seeJson(['id' => $book1->id]);
    }
    public function testLimitAndOffset()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books?limit=1&offset=2");
        $response->assertResponseStatus(200);
    }
    public function testSearchByTitle()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books?search={$book->title}");
        $response->assertResponseStatus(200);
    }
    public function testSortBooksInDescending()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books?sort_desc");
        $response->assertResponseStatus(200);
    }
    public function testSortBooksInAscending()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $author = factory(Author::class)->create();
        $this->be($user);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books?sort_asc");
        $response->assertResponseStatus(200);
    }
    public function testuserCanNotViewBooksWithoutAuth()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $book = factory(Book::class)->create();
        $book1 = factory(Book::class)->create();
        $response = $this->get("/api/v1/books");
        $response->assertResponseStatus(401);
    }

    public function testuserCanViewOneBook()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $response = $this->get("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(200);
        $response->seeJson(['title' => $book->title]);
    }
    public function testuserCanNotViewOneBookWithoutAuth()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $book = factory(Book::class)->create();
        $response = $this->get("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(401);
    }
    public function testViewNonExistingBook()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $response = $this->get("/api/v1/books/207");
        $response->assertResponseStatus(404);
    }

    public function testUserCanUpdateABookWithAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $book = factory(Book::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/books/{$book->id}", ["title" => "New Title"]);
        $updatedBook = $this->get("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(200);
        $updatedBook->seeJson(["title" => "New Title"]);
    }
    public function testUserCanUpdateABookWithoutAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $book = factory(Book::class)->create();
        $response = $this->put("/api/v1/books/{$book->id}", ["title" => "New Title"]);
        $updatedBook = $this->get("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(401);
    }
    public function testUserUpdatBookNotExist()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $response = $this->put("/api/v1/books/226", ["title" => "New Title"]);
        $response->assertResponseStatus(404);
    }

    public function testUserCanDeleteBookWithAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $response = $this->delete("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(204);
    }
    public function testUserCanNotDeleteBookWithoutAuthorization()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $book = factory(Book::class)->create();
        $response = $this->delete("/api/v1/books/{$book->id}");
        $response->assertResponseStatus(401);
    }

    public function testDeleteBookNotExist()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $book = factory(Book::class)->create();
        $response = $this->delete("/api/v1/books/224");
        $response->assertResponseStatus(404);
    }

}
