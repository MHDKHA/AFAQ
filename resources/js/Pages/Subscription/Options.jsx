// resources/js/Pages/Subscription/Options.jsx
import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function SubscriptionOptions() {
    const { data, setData, post, processing, errors } = useForm({
        plan: 'monthly',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('subscribe.process'));
    };

    return (
        <MainLayout title="Subscription Options">
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h1 className="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Unlock Full Assessment Reports
                        </h1>
                        <p className="mt-4 text-xl text-gray-500">
                            Get detailed insights, recommendations, and expert analysis
                        </p>
                    </div>

                    <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div className="px-4 py-5 sm:p-6">
                            <form onSubmit={handleSubmit}>
                                <div className="space-y-5">
                                    <div>
                                        <div className="flex items-center">
                                            <input
                                                id="monthly"
                                                name="plan"
                                                type="radio"
                                                checked={data.plan === 'monthly'}
                                                onChange={() => setData('plan', 'monthly')}
                                                className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                            />
                                            <label htmlFor="monthly" className="ml-3 flex flex-col">
                                                <span className="text-sm font-medium text-gray-900">Monthly Plan</span>
                                                <span className="text-sm text-gray-500">$29/month</span>
                                            </label>
                                        </div>
                                        <div className="mt-4 ml-6 pl-1 text-sm text-gray-500">
                                            <ul className="list-disc space-y-2">
                                                <li>Full access to all assessment tools</li>
                                                <li>Detailed reports and recommendations</li>
                                                <li>Cancel anytime</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div>
                                        <div className="flex items-center">
                                            <input
                                                id="annual"
                                                name="plan"
                                                type="radio"
                                                checked={data.plan === 'annual'}
                                                onChange={() => setData('plan', 'annual')}
                                                className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                            />
                                            <label htmlFor="annual" className="ml-3 flex flex-col">
                                                <span className="text-sm font-medium text-gray-900">Annual Plan (Save 20%)</span>
                                                <span className="text-sm text-gray-500">$278/year</span>
                                            </label>
                                        </div>
                                        <div className="mt-4 ml-6 pl-1 text-sm text-gray-500">
                                            <ul className="list-disc space-y-2">
                                                <li>All features in the monthly plan</li>
                                                <li>Priority support</li>
                                                <li>Advanced analytics</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div className="mt-8">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        {processing ? 'Processing...' : 'Subscribe Now'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
