import React from "react";

const ReviewProduct = props => (
    // @include('layouts.errors')

    <form action={`/products/${props.product_id}/review`} method="POST">
        <div className="form-group">
            <label>
                <select name="rating" className="form-control">
                    <option value="">Choose a rating</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </label>
        </div>

        <div className="form-group">
            <textarea
                className="form-control"
                name="content"
                type="text"
                placeholder="Your review..."
            />
        </div>

        <div className="form-group pull-right">
            <input type="submit" className="form-group btn btn-primary" />
        </div>
    </form>
);
export default ReviewProduct;
