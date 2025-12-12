<?php
            if (isset($_SESSION['pesan'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo $_SESSION['pesan'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            
                unset($_SESSION['pesan']);
            }
            ?>