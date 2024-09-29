@if ($message = session('success'))
    <?php
    alert()->success('server message', $message);
    session()->forget('success');
    ?>
@elseif ($error = session('error'))
    <?php alert()->warning('server mesage', $error); ?>
@endif
