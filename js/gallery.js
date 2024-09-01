document.addEventListener('DOMContentLoaded', function () {
    var popups = document.querySelectorAll('.deleteImage');
    var popup = document.getElementById('popup-intranet-page');
    var span = document.getElementsByClassName('close-intranet-page')[0];
    var textarea = document.getElementById('editPhrase-intranet-page');
    var imageInput = document.getElementById('editImage-intranet-page');
    var saveButton = document.getElementById('saveChanges-intranet-page');
    var currentPhraseIndex = null;
    var fileinputbutton = document.getElementById('fileButton-intranet-page')

    fileinputbutton.addEventListener('click', function() {
        popup.style.display = 'block';
    })

    popups.forEach(function(button) {
        button.addEventListener('click', function() {
            link = button.getAttribute('data-index');
            $.post('php/Sample/removeGalerie.php', { link: link }, function(response) {
                window.location.reload();
            });
        });
    });

    span.onclick = function () {
        popup.style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target == popup) {
            popup.style.display = 'none';
        }
    }

    saveButton.addEventListener('click', function() {
        if (currentPhraseIndex !== null) {
            var updatedPhrase = textarea.value;
            var updatedImage = imageInput.value;
            document.querySelectorAll('.overlay-intranet-page .text-intranet-page')[currentPhraseIndex].innerText = updatedPhrase;
            document.querySelectorAll('.image-container-intranet-page img')[currentPhraseIndex].src = updatedImage;
            phrases[currentPhraseIndex] = updatedPhrase;
            images[currentPhraseIndex] = updatedImage;
            popup.style.display = 'none';
        } else {
            var updatedPhrase = textarea.value;
            var updatedImage = imageInput.value;
            $.post('php/Sample/addGalerie.php', { phrase: updatedPhrase, image: updatedImage }, function(response) {
                window.location.reload();
            });
            popup.style.display = 'none';
        }
    });
});