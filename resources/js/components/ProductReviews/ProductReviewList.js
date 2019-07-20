import React from "react";

const CardHeader = props => (
    <div className="card-header">
        <div className="lead">
            Reviews{" "}
            {props.score !== "0.00" ? <span>(Average {props.score})</span> : ""}
        </div>
    </div>
);

const CardFooter = props => (
    <div className="card-footer">
        {/* @if($permissionToReview)
                        @include('layouts.errors')

                        <form action="{ route('reviewCreate', product.id) }" method='POST'>
                            { csrf_field() }
                            <div className="form-group">
                                <label>
                                    <select name="rating" className='form-control'>
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
                                <textarea className='form-control' name='content' type="text" placeholder="Your review..."></textarea>
                            </div>

                            <div className="form-group pull-right">
                                <input type="submit" className='form-group btn btn-primary'>
                            </div>
                        </form>
                    @endif */}
    </div>
);

const ProductReviewList = props => {
    const { score, reviews } = props;
    console.log(reviews);

    if (reviews.length < 1) {
        return (
            <div className="card">
                <CardHeader score={score} />

                <div className="card-body">
                    <div className="card-text">
                        No reviews for this product.
                    </div>
                </div>

                <CardFooter />
            </div>
        );
    } else {
        return (
            <div className="card">
                <CardHeader score={score} />

                <div className="card-body">
                    <div className="card-text">
                        {reviews.map(
                            ({
                                content,
                                created_at,
                                deleted_at,
                                id,
                                product_id,
                                flagged_review_decided_by,
                                flagged_review_decision_reason,
                                score,
                                short_content,
                                updated_at,
                                user_id,
                                is_flagged_exceeding_limit
                            }) => (
                                <div key={id} className="card">
                                    {!is_flagged_exceeding_limit ? (
                                        <div>
                                            <div className="card-body">
                                                <div className="card-text">
                                                    <div className="float-left">
                                                        Product Rated {score} /
                                                        5
                                                        {/* @if(Auth::check() &&
                                                    auth().user().id ===
                                                    $review.user_id) by{" "}
                                                    <strong>you</strong>
                                                    @endif */}
                                                    </div>

                                                    <div className="float-right">
                                                        Posted {created_at}
                                                    </div>

                                                    <br />
                                                    <br />

                                                    <p>{content}</p>
                                                </div>
                                            </div>
                                            <div className="card-footer">
                                                <a
                                                    href="#"
                                                    className="pull-right btn btn-sm btn-default"
                                                >
                                                    <small>
                                                        Flag this review
                                                    </small>
                                                </a>
                                                <div className="clearfix" />
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="card-body">
                                            <small>
                                                This comment is currently under
                                                review.
                                            </small>
                                        </div>
                                    )}
                                </div>
                            )
                        )}
                    </div>
                </div>

                <CardFooter />
            </div>
        );
    }
};

export default ProductReviewList;
