import React from "react";

const initialState = {
    sort_by: "",
    min_price: "",
    max_price: "",
    query: ""
};

class ProductsSearch extends React.Component {
    state = { ...initialState };

    handleChange = event => {
        const { name, value } = event.target;
        this.setState({ [name]: value }, () => {
            return this.props.handleProductSearch({ ...this.state });
        });
    };

    render() {
        return (
            <form
                onChange={event => this.handleChange(event)}
                className="form-inline"
                action=""
                method="GET"
            >
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <div className="input-group-text">
                                <i className="fa fa-sort" aria-hidden="true" />
                            </div>
                        </div>
                        <select name="sort_by" className="form-control">
                            <option value="">Sort by</option>
                            <option value="pop">Most Popular</option>
                            <option value="top">Top Rated</option>
                            <option value="low">Lowest Price</option>
                            <option value="hig">Highest Price</option>
                        </select>
                    </div>
                </div>

                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <div className="input-group-text">£</div>
                        </div>
                        <input
                            type="number"
                            min="0.01"
                            step="0.01"
                            max="2500"
                            name="min_price"
                            className="form-control"
                            placeholder="Min"
                        />
                    </div>
                </div>

                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <div className="input-group-text">£</div>
                        </div>
                        <input
                            type="number"
                            min="0.01"
                            step="0.01"
                            max="2500"
                            name="max_price"
                            className="form-control"
                            placeholder="Max"
                        />
                    </div>
                </div>

                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <div className="input-group-text">
                                <i
                                    className="fa fa-search"
                                    aria-hidden="true"
                                />
                            </div>
                        </div>
                        <input
                            name="query"
                            type="text"
                            className="form-control"
                            placeholder="Search..."
                        />
                    </div>
                </div>
            </form>
        );
    }
}

export default ProductsSearch;
