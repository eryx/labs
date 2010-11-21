<?php
foreach ($feeds as $entry) {
    echo "<h3><a href=\"/{$this->inst}/view/?id={$entry['id']}\">{$entry['title']}</a></h3>";
    echo "<div>{$entry['summary']}</div>";
    echo "<div>{$entry['content']}</div>";
}
