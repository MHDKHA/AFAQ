import React from 'react';
import { useForm } from '@inertiajs/react';
import { Head } from '@inertiajs/react';

const RegistrationForm = () => {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        company_name: '',
        address: '',
        city: '',
        country: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('registration.store'));
    };

    return (
        <>
            <Head title="User Registration" />

            <div className="container">
                <div className="visual-section">
                    <div className="decoration dec-1"></div>
                    <div className="decoration dec-2"></div>

                    <div className="visual-content">
                        <div className="ebook-preview">
                            <div className="ebook-cover">
                                <img src="/images/registration-visual.jpg" alt="Registration Visual" />
                            </div>
                        </div>

                        <h2>Join Our Platform</h2>
                        <p>The complete solution for your business needs</p>

                        <div className="highlights">
                            <div className="highlight-item">
                                <div className="highlight-icon">✓</div>
                                <div className="highlight-text">Access to premium resources</div>
                            </div>
                            <div className="highlight-item">
                                <div className="highlight-icon">✓</div>
                                <div className="highlight-text">Personalized dashboard & analytics</div>
                            </div>
                            <div className="highlight-item">
                                <div className="highlight-icon">✓</div>
                                <div className="highlight-text">Connect with industry experts</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="form-section">
                    <div className="form-header">
                        <h2>User Registration</h2>
                        <p>Please complete the registration form below to continue.</p>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="form-group-header">
                            <h3>Personal Information</h3>
                        </div>

                        <div className="form-group">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                className="form-control"
                                placeholder="Full Name*"
                                required
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                            />
                            {errors.name && <p className="error-message">{errors.name}</p>}
                        </div>

                        <div className="form-group">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                className="form-control"
                                placeholder="Email Address*"
                                required
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                            />
                            {errors.email && <p className="error-message">{errors.email}</p>}
                        </div>

                        <div className="form-group">
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                className="form-control"
                                placeholder="Phone Number*"
                                required
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                            />
                            {errors.phone && <p className="error-message">{errors.phone}</p>}
                        </div>

                        <div className="form-group-header">
                            <h3>Company Information</h3>
                        </div>

                        <div className="form-group">
                            <input
                                id="company_name"
                                name="company_name"
                                type="text"
                                className="form-control"
                                placeholder="Company Name*"
                                required
                                value={data.company_name}
                                onChange={(e) => setData('company_name', e.target.value)}
                            />
                            {errors.company_name && <p className="error-message">{errors.company_name}</p>}
                        </div>

                        <div className="form-group">
                            <input
                                id="address"
                                name="address"
                                type="text"
                                className="form-control"
                                placeholder="Address"
                                value={data.address}
                                onChange={(e) => setData('address', e.target.value)}
                            />
                            {errors.address && <p className="error-message">{errors.address}</p>}
                        </div>

                        <div className="form-row">
                            <div className="form-group half">
                                <input
                                    id="city"
                                    name="city"
                                    type="text"
                                    className="form-control"
                                    placeholder="City"
                                    value={data.city}
                                    onChange={(e) => setData('city', e.target.value)}
                                />
                                {errors.city && <p className="error-message">{errors.city}</p>}
                            </div>

                            <div className="form-group half">
                                <input
                                    id="country"
                                    name="country"
                                    type="text"
                                    className="form-control"
                                    placeholder="Country"
                                    value={data.country}
                                    onChange={(e) => setData('country', e.target.value)}
                                />
                                {errors.country && <p className="error-message">{errors.country}</p>}
                            </div>
                        </div>

                        <button
                            type="submit"
                            className="btn-primary"
                            disabled={processing}
                        >
                            {processing ? 'Processing...' : 'Complete Registration'}
                        </button>
                    </form>
                </div>
            </div>

            <style jsx>{`
                :root {
                    --primary: #4a2a81;
                    --primary-light: #6a3fb8;
                    --accent: #ff9500;
                    --dark: #1a1a2e;
                    --light: #ffffff;
                    --background: #f8f9fc;
                    --gray-light: #f1f2f6;
                    --shadow: 0 10px 30px rgba(0,0,0,0.08);
                    --transition: all 0.3s ease;
                }

                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                body {
                    background-color: var(--background);
                    color: var(--dark);
                    line-height: 1.6;
                    overflow-x: hidden;
                }

                .container {
                    display: flex;
                    min-height: 100vh;
                    width: 100%;
                }

                .visual-section {
                    flex: 0 0 45%;
                    background-color: var(--primary);
                    color: var(--light);
                    position: relative;
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    padding: 3rem;
                }

                .visual-content {
                    position: relative;
                    z-index: 2;
                    max-width: 550px;
                }

                .ebook-preview {
                    perspective: 1000px;
                    margin-bottom: 2rem;
                }

                .ebook-cover {
                    position: relative;
                    transform: rotateY(15deg) rotateX(5deg);
                    transition: var(--transition);
                    box-shadow: var(--shadow);
                    max-width: 90%;
                    border-radius: 8px;
                }

                .ebook-cover:hover {
                    transform: rotateY(5deg) rotateX(0deg);
                }

                .ebook-cover img {
                    max-width: 100%;
                    border-radius: 8px;
                    display: block;
                }

                .form-section {
                    flex: 0 0 55%;
                    padding: 2rem 6rem;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    overflow-y: auto;
                }

                .form-header {
                    margin-bottom: 2rem;
                }

                .form-header h2 {
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: var(--primary);
                    margin-bottom: 1rem;
                }

                .form-header p {
                    color: #6c757d;
                    font-size: 1.1rem;
                }

                .form-group-header {
                    margin: 1.5rem 0 1rem;
                }

                .form-group-header h3 {
                    color: var(--primary);
                    font-size: 1.3rem;
                    font-weight: 600;
                }

                .form-group {
                    margin-bottom: 1.5rem;
                }

                .form-row {
                    display: flex;
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                }

                .form-group.half {
                    flex: 1;
                    margin-bottom: 0;
                }

                .form-control {
                    width: 100%;
                    padding: 1rem 1.2rem;
                    font-size: 1rem;
                    line-height: 1.5;
                    color: var(--dark);
                    background-color: var(--light);
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    transition: var(--transition);
                }

                .form-control:focus {
                    border-color: var(--primary-light);
                    outline: 0;
                    box-shadow: 0 0 0 3px rgba(74, 42, 129, 0.1);
                }

                .btn-primary {
                    display: block;
                    width: 100%;
                    padding: 1rem;
                    font-size: 1.1rem;
                    font-weight: 600;
                    text-align: center;
                    color: var(--light);
                    background-color: var(--accent);
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: var(--transition);
                    margin-top: 2rem;
                }

                .btn-primary:hover:not(:disabled) {
                    background-color: #e68600;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 15px rgba(255, 149, 0, 0.2);
                }

                .btn-primary:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                }

                .decoration {
                    position: absolute;
                    opacity: 0.1;
                    z-index: 1;
                }

                .dec-1 {
                    width: 500px;
                    height: 500px;
                    border-radius: 50%;
                    background: linear-gradient(45deg, var(--accent), #ff5e62);
                    top: -200px;
                    right: -200px;
                }

                .dec-2 {
                    width: 300px;
                    height: 300px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #4facfe, #00f2fe);
                    bottom: -100px;
                    left: -100px;
                }

                .highlights {
                    margin-top: 1.5rem;
                }

                .highlight-item {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1rem;
                }

                .highlight-icon {
                    margin-right: 1rem;
                    width: 24px;
                    height: 24px;
                    background-color: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                }

                .highlight-text {
                    font-size: 1rem;
                }

                .error-message {
                    color: #e53e3e;
                    font-size: 0.875rem;
                    margin-top: 0.5rem;
                }

                @media (max-width: 1200px) {
                    .form-section {
                        padding: 2rem 3rem;
                    }
                }

                @media (max-width: 992px) {
                    .container {
                        flex-direction: column;
                    }

                    .visual-section, .form-section {
                        flex: 0 0 100%;
                    }

                    .visual-section {
                        padding: 3rem 2rem;
                        justify-content: center;
                    }

                    .visual-content {
                        max-width: 100%;
                        text-align: center;
                        margin: 0 auto;
                    }

                    .ebook-preview {
                        margin-bottom: 1.5rem;
                    }

                    .ebook-cover {
                        margin: 0 auto;
                    }

                    .form-section {
                        padding: 2rem;
                    }

                    .form-row {
                        flex-direction: column;
                        gap: 1.5rem;
                    }
                }
            `}</style>
        </>
    );
};

export default RegistrationForm;
