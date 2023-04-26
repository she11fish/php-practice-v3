<?php
    class Recipe {
        private $userid;
        private $name;
        private $pictureURL;
        private $ingredients;
        private $directions;
        private $public;
        private $favorite;
        function __construct($userid, $name, $pictureURL, $ingredients, $directions, $public, $favorite) {
            $this->userid = $userid;
            $this->name = $name;
            $this->pictureURL = $pictureURL;
            $this->ingredients = $ingredients;
            $this->directions = $directions;
            $this->public = $public;
            $this->favorite = $favorite;
        }

        function get_userid() {
            return $this->userid;
        }

        function get_name() {
            return $this->name;
        }

        function get_pictureURL() {
            return $this->pictureURL;
        }

        function get_ingredients() {
            return explode(',', $this->ingredients);
        }

        function get_directions() {
            return explode('.', $this->directions);
        }

        function get_public() {
            return $this->public;
        }

        function get_favorite() {
            return $this->favorite;
        }
    }
?>