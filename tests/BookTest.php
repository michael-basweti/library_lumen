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
    // public function userCanViewAllExistingBooks()
    // {
    //     $book = factory(Book::class)->create();
    //     $book1 = factory(Book::class)->create();
    //     $response = $this->get("/api/v1/books");
    //     $response->assertResponseStatus(200);
    //     $response->seeJson(['title' => $book->title]);
    //     $response->seeJson(['isbn' => $book1->isbn]);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function userCanSortBooks()
    // {
    //     factory(Book::class, 8)->create();
    //     $response = $this->get("/api/v1/books?sort=title_asc");
    //     $response->assertResponseStatus(200);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function userCanfilterBooksByAuthor()
    // {
    //     $author = factory(Author::class)->create();
    //     factory(Book::class)->create(['author_id' => $author->id]);
    //     $response = $this->get("/api/v1/books?author={$author->name}");
    //     $response->assertResponseStatus(200);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function userCansearchBookBytitle()
    // {
    //     $book = factory(Book::class)->create();
    //     $response = $this->get("/api/v1/books?search={$book->title}");
    //     $response->assertResponseStatus(200);
    //     $response->seeJson(['title' => $book->title]);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function userCanUseLimitAndOffsetToQueryBooks()
    // {
    //     factory(Book::class, 50)->create();
    //     $response = $this->get("/api/v1/books?offset=8&limit=10");
    //     $response->assertResponseStatus(200);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function userCanViewSingleBook()
    // {
    //     $book = factory(Book::class)->create();
    //     $response = $this->get("/api/v1/books/{$book->isbn}");
    //     $response->assertResponseStatus(200);
    //     $response->seeJson(['isbn' => $book->isbn]);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function tryingToGetNonExistingBookReturns404()
    // {
    //     $response = $this->get("/api/v1/books/isbnrandom");
    //     $response->assertResponseStatus(404);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    public function authUserCanUpdateABook()
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
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function tryingToUpdateNonExistingBookReturns404()
    // {
    //     $user = factory(User::class)->create();
    //     $this->be($user);
    //     $response = $this->put("/api/v1/books/isbnRandom", ["title" => "New Title"]);
    //     $response->assertResponseStatus(404);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function authUserCanAddABook()
    // {
    //     $user = factory(User::class)->create();
    //     $author = factory(Author::class)->create();
    //     $this->be($user);
    //     $book = [
    //         'title' => 'test title',
    //         'description' => 'test description',
    //         'author_id' => $author->id,
    //         'genre' => 'crime-fiction',
    //         'isbn' => 'isbn'
    //     ];
    //     $response = $this->post('/api/v1/books', $book);
    //     $response->assertResponseStatus(201);
    //     $response->seeJson(["title" => "test title"]);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function tryingToAddExistingBookReturns422()
    // {
    //     $user = factory(User::class)->create();
    //     $author = factory(Author::class)->create();
    //     $this->be($user);
    //     $book1 = factory(Book::class)->create();
    //     $book = [
    //         'title' => 'test title',
    //         'description' => 'test description',
    //         'author_id' => $author->id,
    //         'genre' => 'crime-fiction',
    //         'isbn' => $book1->isbn
    //     ];
    //     $response = $this->post('/api/v1/books', $book);
    //     $response->assertResponseStatus(422);
    //     $response->seeJson(["isbn" => [
    //         "The isbn has already been taken."
    //     ]]);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function authUserCanDeleteBook()
    // {
    //     $user = factory(User::class)->create();
    //     $this->be($user);
    //     $book = factory(Book::class)->create();
    //     $response = $this->delete("/api/v1/books/{$book->isbn}");
    //     $response->assertResponseStatus(204);
    // }
    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function tryingToDeleteNonExistingBookReturns404()
    // {
    //     $user = factory(User::class)->create();
    //     $this->be($user);
    //     $response = $this->delete("/api/v1/books/isbnRandom");
    //     $response->assertResponseStatus(404);
    // }
}
