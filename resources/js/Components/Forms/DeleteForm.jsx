export default function DeleteForm({ onClose, onDelete, isProcessing }) {
    return (
        <div className="p-3">
            <p className="text-dark">
                Are you sure you want to delete this?
            </p>
            <div className="d-flex justify-content-end gap-2">
                <button
                    className="btn btn-secondary"
                    onClick={onClose}
                >
                    Cancel
                </button>
                <button
                    className="btn btn-danger"
                    onClick={() => onDelete()}
                    disabled={isProcessing}
                >
                    Delete
                </button>
            </div>
        </div>
    );
}