<div class="col">
    <div class="card h-100">
        <img src="<?php echo $cardImages[0]['url'] ?>" class="card-img-top" alt="...">





        <div class="card-body">

            <div class="card-header">
                <?php echo $class;
                    if ($duration) {
                        echo "  -> duration: $duration";
                    }

                    echo "<ul class='list-group list-group-flush'> <li class='list-group-item'>Rating $rating</li>";

                ?>
            </div>

            <h5 class="card-title"> <?php echo $headline ?></h5>

            <details>
                <summary>Summary</summary>
                <p class="card-text"><?php echo $body ?></p>
            </details>


            <details>
                <summary>Cast</summary>
                <ul class="list-group list-group-flush">
                    <details>
                        <summary>Actors</summary>
                        <?php
                        foreach ($cast as $actors) {
                            foreach ($actors as $actor) {
                                echo "<li class='list-group-item'>$actor</li>";
                            }
                        }
                        ?>
                    </details>

                    <details>
                        <summary>Directors</summary>
                        <?php
                        foreach ($directors as $director) {
                            foreach ($director as $directorName) {
                                echo "<li class='list-group-item'>$directorName</li>";
                            }
                        }
                        ?>
                    </details>

                </ul>
            </details>



        </div>
        <div class="card-footer">
            <small class="text-muted"><?php echo $lastUpdated ?></small>
        </div>
    </div>
</div>


<style>
    .bg-text {
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
        color: white;
        font-weight: bold;
        border: 3px solid #f1f1f1;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        width: 80%;
        padding: 20px;
        text-align: center;
    }
</style>
