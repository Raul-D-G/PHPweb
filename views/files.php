<nav aria-label="Page navigation example">

    <?php
        if ($scroll) {
            echo "<ul class='pagination'><li class='page-item'><a class='page-link' href='?scroll=$scroll'>Next</a></li></ul>";
        }
    ?>


</nav>
<div class="row row-cols-1 row-cols-md-3 g-4">
    {{component}}
</div>

