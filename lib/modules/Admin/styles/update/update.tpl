<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Updates"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Updates"|translate}</li>
            <li class="active">{-"Check for updates"|translate}</li>
        </ol>
        {-if $hasUpdate }
            <div class="panel panel-default panel-danger">
                <div class="panel-heading">{-"Update available"|translate }</div>
                <div class="panel-body">
                    <p>{-"There is a new Version available. Click on the update button to automatically update your Cunity"|translate }</p>
                    <button type="button" class="btn btn-danger">Update now</button>
                </div>
            </div>
        {-else }
        <div class="panel panel-default panel-success">
            <div class="panel-heading">No update available</div>
            <div class="panel-body">
                <p>Congratulations, you already have the latest Cunity version</p>
            </div>
        </div>
        {-/if}
    </div>
</div>