<h1 class="page-header">{-$page.title}</h1>
{-$page.content}
{-if !empty($user) && $page.comments eq 1}
    <div id="page-comments">

    </div>
{-/if}