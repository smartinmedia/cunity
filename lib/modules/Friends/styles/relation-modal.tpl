<div class="modal fade" id="relationship-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-category addasfriend hidden">{-"Send Friend Request"|translate}</h4>
                <h4 class="modal-title modal-category removerequest hidden">{-"Remove Friend Request"|translate}</h4>
                <h4 class="modal-title modal-category removefriend hidden">{-"Relationshipstatus"|translate}</h4>
                <h4 class="modal-title modal-category confirmfriend hidden">{-"Confirm a friendship"|translate}</h4>
                <h4 class="modal-title modal-category blockperson hidden">{-"Block person"|translate}</h4>
                <h4 class="modal-title modal-category unblock hidden">{-"Unblock person"|translate}</h4>
                <h4 class="modal-title modal-category relationship hidden">{-"Unblock person"|translate}</h4>
            </div>
            <div class="modal-loader block-loader loader"></div>
            <div class="modal-body hidden">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#" class="thumbnail user-data-link">
                            <img src="" alt="" class="user-data-image">
                        </a>
                    </div>
                    <div class="col-md-9 addasfriend hidden modal-category">
                        <p>{-"Are You sure You want to add this user as a friend?"|translate}</p>

                        <p>{-"Please send friend requests only to people you really know!"|translate}</p>
                    </div>
                    <div class="col-md-9 removerequest hidden modal-category">
                        <p>{-"Are You sure You want to remove the friend request?"|translate}</p>

                        <p>{-"Please send friend requests only to people you really know!"|translate}</p>
                    </div>
                    <div class="col-md-9 removefriend hidden modal-category">
                        <p>{-"Are You sure You want to remove this user as a friend?"|translate}</p>

                        <p>{-"Please send friend requests only to people you really know!"|translate}</p>
                    </div>
                    <div class="col-md-9 confirmfriend hidden modal-category">
                        <p>{-"Are You sure you want to confirm this friendship?"|translate}</p>
                    </div>
                    <div class="col-md-9 relationship hidden modal-category">
                        <p class="help-block">{-"Please select a status for your relationship"|translate}</p>
                        <select class="form-control" id="relationshipSelect">
                            <option value="3">{-"In a relationship"|translate}</option>
                            <option value="4">{-"Engaged"|translate}</option>
                            <option value="5">{-"Married"|translate}</option>
                        </select>
                    </div>
                    <div class="col-md-9 blockperson hidden modal-category">
                        <p>{-"Are You sure You want to block this user?"|translate}</p>
                    </div>
                    <div class="col-md-9 unblock hidden modal-category">
                        <p>{-"Are You sure You want to unblock this user?"|translate}</p>

                        <p>{-"Then You both are allowed to communicate"|translate}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer addasfriend hidden modal-category">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="addFriend();">{-"Add as friend"|translate}</button>
            </div>
            <div class="modal-footer relationship hidden modal-category">
                <a href="javascript:void(0);" onclick="changeRelationship(2);"
                   class="text-danger pull-left">{-"End Relationship"|translate}</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="changeRelationship($('#relationshipSelect').val());">{-"Save"|translate}</button>
            </div>
            <div class="modal-footer removerequest hidden modal-category">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="removeFriend();">{-"Remove it!"|translate}</button>
            </div>
            <div class="modal-footer removefriend hidden modal-category">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="removeFriend();">{-"I'm Sure!"|translate}</button>
            </div>
            <div class="modal-footer confirmfriend hidden modal-category">
                <a href="javascript:void(0);" onclick="removeFriend();"
                   class="text-danger pull-left">{-"Delete Request"|translate}</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary" onclick="confirmFriend();">{-"Confirm"|translate}</button>
            </div>
            <div class="modal-footer blockperson hidden modal-category">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="blockFriend();">{-"Sure, do it!"|translate}</button>
            </div>
            <div class="modal-footer unblock hidden modal-category">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="removeFriend();">{-"Sure, do it!"|translate}</button>
            </div>
        </div>
    </div>
</div>            