import React from "react";

import ReviewProduct from "./ReviewProduct";

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
        {props.permissionToReview ? <ReviewProduct {...props} /> : ""}
    </div>
);

const ProductReviewList = props => {
    const { score, reviews, current_user, userAuthenticated, product } = props;
    const { permissionToReview } = product;

    if (reviews.length < 1) {
        return (
            <div className="card">
                <CardHeader score={score} />

                <div className="card-body">
                    <div className="card-text">
                        No reviews for this product.
                    </div>
                </div>

                <CardFooter
                    permissionToReview={permissionToReview}
                    product_id={product.id}
                />
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
                                                        {userAuthenticated &&
                                                        current_user.user.id ===
                                                            reviews.user_id ? (
                                                            <strong>you</strong>
                                                        ) : (
                                                            ""
                                                        )}
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

                <CardFooter
                    permissionToReview={permissionToReview}
                    product_id={product.id}
                />
            </div>
        );
    }
};

export default ProductReviewList;
