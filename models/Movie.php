<?php

class Movie {

    public $id;
    public $title;
    public $description;
    public $image;
    public $trailer;
    public $category;
    public $length;
    public $users_id;

    public function imageGenerateName(){
        return bin2hex(random_bytes(60)) . ".jpeg"; 
    }

}

interface MovieDAOInterface {

    public function buildMovie($data);
    public function FindAll();
    public function getLatestMovies();
    public function getMoviesByCategory($category);
    public function getMoviesByUserId($id);
    public function findById($id);
    public function findyByTitle($title);
    public function create(Movie $movie);
    public function update(Movie $movie);
    public function destroy($id);
    

}