<!-- This will be for the website design
 B20250035 Alya Sofiya binti Haji Rosaimi
 B20250113 Nur Aini Jamilah Mohamad Johnny
 B20250176 Ahmad Ridhuan bin Mohamad Johnny -->

<?php
require_once 'dbconnect.php';
include 'header.php';
?>   
    
    <h2>&nbsp;Welcome to Booknooks!</h2>
    
    
    <main class="maintitle">
        <div class="maincontainer">
            <div class="maintextwrapper">
                <div class="decorativeblub"></div>
                <div class="maintext">
                    <h1>Ditch notepads and keep track of your reading using Booknooks today!</h1>
                </div>
            </div>
            <div class="mainimage">
                <img src="assets/bookmarked.png" alt="Bookmarked Book" width="400" height="400">
            </div>
        </div>
    </main>
    
    
    <div class="action-footer-bar">
            <button type="button" class="add-book-btn2" onclick="window.location.href='bookform.html'">
                Add a New Book Now!
            </button>
    </div>

    <?php include 'footer.php'; ?>
    