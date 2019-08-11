import React from "react";

const initialState = {
    sort_by: "",
    min_price: "",
    max_price: "",
    query: ""
};

class ProductsSearch extends React.Component {
    state = { ...initialState };

    componentDidMount() {
        const {
            sort_by,
            min_price,
            max_price,
            query
        } = this.props.searchParams;

        this.setState({
            sort_by,
            min_price,
            max_price,
            query
        });
    }

    handleChange = event => {
        const { name, value } = event.target;
        this.setState({ [name]: value }, () => {
            return this.props.handleProductSearch({ ...this.state });
        });
    };

    render() {
        const { sort_by, min_price, max_price, query } = this.state;

        return (
            <form className="form-inline">
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <div className="input-group-text">
                                <i className="fa fa-sort" aria-hidden="true" />
                            </div>
                        </div>
                        <select
                            name="sort_by"
                            value={sort_by}
                            onChange={e => this.handleChange(e)}
                            className="form-control"
                        >
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
                            value={min_price}
                            onChange={e => this.handleChange(e)}
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
                            value={max_price}
                            onChange={e => this.handleChange(e)}
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
                            value={query}
                            onChange={e => this.handleChange(e)}
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
