<div class="popup">
    <div class="contenuPopup">
        <form method="post">
            <input type="submit" value="" class="boutonQuitPopup">
            <input type="hidden" name="popup" value="<?php echo htmlspecialchars($popup); ?>">
        </form>
        <p class="titrePopup"><?php echo htmlspecialchars($htmlSInscrire); ?></p>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept=".png" required>
            <input type="submit">
        </form>
    </div>
</div>