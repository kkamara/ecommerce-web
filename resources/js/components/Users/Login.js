import React, { Component, Fragment } from "react";
import { connect } from "react-redux";
import { withRouter, Redirect } from "react-router-dom";

import { loginUser } from "../../requests/user";

class Login extends Component {
    state = {
        email: "",
        password: "",
        errors: [],
        showErrors: true
    };

    componentDidMount() {
        const { fetched, isLoaded, user } = this.props.current_user;
        const { history } = this.props;

        if (typeof user !== "undefined") {
            history.push("/404");
        }
    }

    getErrors = () => {
        const { email, password } = this.state;
        let errors = [];

        if (!email) {
            errors.push("Email not given");
        }
        if (!password) {
            errors.push("Password not given");
        }

        this.setErrors(errors);

        return errors;
    };

    onChange = e => {
        const { name, value } = e.target;

        this.setState({ [name]: value });
    };

    onSubmit = e => {
        e.preventDefault();

        if (0 < this.getErrors().length) {
            if (!this.state.showErrors) {
                this.toggleShowErrorState();
            }
        } else {
            const { email, password } = this.state;

            this.props.dispatch({
                type: "POST_LOGIN_USER",
                payload: loginUser(email, password)
            });
        }
    };

    setErrors = errors => {
        this.setState({ errors });
    };

    toggleShowErrorState = () => {
        const { showErrors } = this.state;

        this.setState({ showErrors: !showErrors });
    };

    onErrorClose = () => {
        this.toggleShowErrorState();
    };

    render() {
        const { email, password, errors, showErrors } = this.state;

        const { user } = this.props.user;

        if (typeof user !== "undefined") {
            return <Redirect to="/" />;
        } else {
            return (
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="card">
                                <div className="card-body">
                                    <div className="col-md-4 offset-md-4">
                                        <div className="card-title">
                                            <a
                                                href="/register"
                                                className="btn btn-info btn-sm"
                                            >
                                                Click here to{" "}
                                                <strong>
                                                    create an account
                                                </strong>
                                            </a>
                                        </div>

                                        {0 < errors.length && showErrors ? (
                                            <div
                                                id="error-container"
                                                className="alert alert-danger"
                                                role="alert"
                                            >
                                                <button
                                                    type="button"
                                                    className="close"
                                                    // data-dismiss="alert"
                                                    aria-label="Close"
                                                    onClick={e =>
                                                        this.onErrorClose()
                                                    }
                                                >
                                                    <span aria-hidden="true">
                                                        &times;
                                                    </span>
                                                </button>

                                                {errors.map((error, index) => (
                                                    <div key={index}>
                                                        {error}
                                                    </div>
                                                ))}
                                                <div className="clearfix" />
                                            </div>
                                        ) : (
                                            <div />
                                        )}

                                        <form onSubmit={e => this.onSubmit(e)}>
                                            <div className="card-text">
                                                <div className="form-group">
                                                    <label>Email</label>
                                                    <input
                                                        type="text"
                                                        className="form-control"
                                                        name="email"
                                                        value={email}
                                                        onChange={e =>
                                                            this.onChange(e)
                                                        }
                                                        placeholder="Email address"
                                                    />
                                                </div>
                                                <div className="form-group">
                                                    <label>Password</label>
                                                    <input
                                                        type="password"
                                                        className="form-control"
                                                        name="password"
                                                        value={password}
                                                        onChange={e =>
                                                            this.onChange(e)
                                                        }
                                                        placeholder="Password"
                                                    />
                                                </div>
                                            </div>
                                            <div className="card-footer">
                                                <input
                                                    type="submit"
                                                    className="btn btn-success pull-right"
                                                    value="Login"
                                                    onSubmit={e =>
                                                        this.onSubmit(e)
                                                    }
                                                />
                                                <div className="clearfix" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    }
}

const mapStateToProps = state => ({
    user: state.user.user
});
export default connect(
    mapStateToProps,
    null
)(withRouter(Login));
