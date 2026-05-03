$(function() {
  $('#copy-email').on('click', function() {
    const email = $('#email-text').text();
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(email).then(() => {
        const $btn = $('#copy-email');
        $btn.text('Copied');
        setTimeout(() => $btn.text('Copy'), 1400);
      });
    } else {
      const $temp = $('<input>');
      $('body').append($temp);
      $temp.val(email).select();
      document.execCommand('copy');
      $temp.remove();
      const $btn = $('#copy-email');
      $btn.text('Copied');
      setTimeout(() => $btn.text('Copy'), 1400);
    }
  });

  // small hover animation for the profile circle
  $('.photo-card').hover(
    function() { $(this).css('transform', 'scale(1.03)'); },
    function() { $(this).css('transform', 'scale(1)'); }
  );
});
