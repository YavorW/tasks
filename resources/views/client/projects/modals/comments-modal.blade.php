<?php 
use App\Models\Project; 
use App\Models\Task; 
?>
<!-- comments modal -->
<script>
  function commentsModal() {
    return {
      comments: [],
      task_id: null,
      edit: null,
      comment_value: null,
      loadComments(task) {
        const modal = new bootstrap.Modal(document.getElementById('comments-modal'));
        this.comments = task.comments;
        this.task_id = task.id;
        modal.show();  
      },
      // изпращане към livewire
      addComment($refs) {
        @this.addComment(this.task_id, $refs.add_comment.value);
        $refs.add_comment.value = '';
      },
      // зареждане на коментара от livewire
      loadComment(comment) {
        this.comments.unshift(comment);
      },
      // редактиране на коментар към livewire
      editComment(comment) {
        const value = this.comment_value;
        // записва само когато няма промяна
        if(value != comment.comment) {
          @this.editComment(this.edit, value)
          comment.comment = value;
        }
        this.edit = null;
        this.comment_value = null;
      },
      deleteComment(comment) {
        @this.deleteComment(comment.id)
        this.comments = this.comments.filter( e => e.id != comment.id);
      }
    }
  }
</script>
<div class="modal fade" id="comments-modal" tabindex="-1" x-data="commentsModal()"
  x-on:load-comments.window="loadComments($event.detail)"
  x-on:load-comment.window="if(task_id === $event.detail.task_id) loadComment($event.detail.comment)">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Коментари</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div>
            <div class="input-group mb-3">
              <textarea class="form-control" placeholder="xxx" x-ref="add_comment"></textarea>
              <button class="input-group-text btn btn-primary" x-on:click="addComment($refs)" title="Добави">
                <i class="fas fa-plus-circle"></i>
              </button>
            </div>
          </div>
          <ol class="list-group">
            <template x-for="(comment, index) in comments" :key="index">
              <li class="list-group-item">
                <div x-show="edit != comment.id">
                  <div class="d-flex justify-content-between align-items-start pe-0">
                    <div class="w-100 pe-2">
                      <div x-text='comment.comment'></div>
                      <div class="d-flex justify-content-between w-100">
                        <div class="fw-bold" x-text="comment.username"></div>
                        <div x-text="dateFormat(comment.updated_at)"></div>
                      </div>
                    </div>
                    <div class="ms-auto text-nowrap">
                      <button class="btn btn-outline-primary me-1"
                        x-on:click="edit = comment.id; comment_value = comment.comment" title="Редактирай"><i
                          class="fa fa-edit"></i></button>
                      <button class="btn btn-outline-danger"
                        x-on:click="if(confirm('Да се изтрие ли?'))  deleteComment(comment)" title="Изтриване"><i
                          class="fa fa-trash"></i></button>
                    </div>
                  </div>
                </div>
                <div x-show="edit == comment.id">
                  <div class="input-group">
                    <textarea class="form-control" placeholder="xxx" x-model="comment_value"></textarea>
                    <button class="input-group-text btn btn-success" x-on:click="editComment(comment)"
                      title="Редактирай">
                      <i class="fas fa-save"></i>
                    </button>
                  </div>
                </div>
              </li>
            </template>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /edit modal -->