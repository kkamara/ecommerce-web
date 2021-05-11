import React from "react";

const initialState = {
    sort_by: "",
    min_price: "",
    max_price: "",
    query: "",
    clearInputsHover: false
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

        if (name == "sort_by") {
            this.setState({ [name]: value }, () => {
                return this.props.handleProductSearch({ ...this.state });
            });
        } else {
            this.setState({ [name]: value });
        }
    };

    handleProductSearch = e => {
        this.props.handleProductSearch({ ...this.state });
    };

    handleKeyDown = e => {
        if (e.key === "Enter") {
            this.handleProductSearch();
        }
    };

    toggleClearHover() {
        this.setState({ clearInputsHover: !this.state.clearInputsHover });
    }

    clearInputs() {
        this.setState(
            { sort_by: "", min_price: "", max_price: "", query: "" },
            () => this.props.handleProductSearch({ ...this.state })
        );
    }

    render() {
        const { resetInputs } = styles;
        const {
            sort_by,
            min_price,
            max_price,
            query,
            clearInputsHover
        } = this.state;

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
                            onKeyDown={this.handleKeyDown}
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
                            onKeyDown={this.handleKeyDown}
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
                            onKeyDown={this.handleKeyDown}
                            type="text"
                            className="form-control"
                            placeholder="Search..."
                        />
                    </div>
                </div>

                <div className="form-group">
                    <div className="input-group">
                        <a
                            onMouseEnter={this.toggleClearHover.bind(this)}
                            onMouseLeave={this.toggleClearHover.bind(this)}
                            onClick={this.clearInputs.bind(this)}
                            className="form-control"
                            style={resetInputs}
                        >
                            {clearInputsHover ? (
                                <i
                                    className="fa fa-window-close"
                                    aria-hidden="true"
                                />
                            ) : (
                                <i className="fa fa-times" aria-hidden="true" />
                            )}
                        </a>
                    </div>
                </div>
            </form>
        );
    }
}

const styles = {
    resetInputs: {
        cursor: "pointer"
    }
};

export default ProductsSearch;
