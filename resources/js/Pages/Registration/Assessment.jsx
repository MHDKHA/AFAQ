import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';

const AssessmentPage = ({ registration, criteria }) => {
    const [formResponses, setFormResponses] = useState({});

    const { post, processing } = useForm({
        formResponses: {}
    });

    // Handle changes to checkbox and text inputs
    const handleInputChange = (criterionId, field, value) => {
        setFormResponses(prev => ({
            ...prev,
            [criterionId]: {
                ...prev[criterionId],
                [field]: value
            }
        }));
    };

    // Save assessment data
    const handleSave = () => {
        post(route('frontend.assessment.save', registration.id), {
            formResponses,
            onSuccess: () => {
                alert('Assessment data saved successfully!');
            }
        });
    };

    return (
        <div className="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
            <Head title="Assessment Form" />

            <div className="relative py-3 sm:max-w-5xl sm:mx-auto">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8">
                        <h1 className="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Assessment Form
                        </h1>
                        <p className="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                            Welcome, {registration.name}! Please complete the assessment below.
                        </p>
                    </div>

                    <div className="bg-white p-6 shadow rounded-lg">
                        {Object.entries(criteria).map(([categoryName, categoryData]) => (
                            <div key={categoryName} className="mb-8">
                                <h2 className="text-2xl font-bold text-gray-900 mb-4 p-2 bg-gray-50 rounded">
                                    {categoryName}
                                </h2>

                                <div className="space-y-6">
                                    {categoryData.map((criterion) => (
                                        <div key={criterion.id} className="p-4 border border-gray-200 rounded-md">
                                            <div className="flex flex-col md:flex-row md:space-x-4">
                                                <div className="flex-1">
                                                    <p className="font-medium text-gray-800">
                                                        {criterion.question}
                                                    </p>
                                                </div>

                                                <div className="mt-4 md:mt-0 md:w-1/4 flex items-center justify-center">
                                                    <label className="inline-flex items-center">
                                                        <input
                                                            type="checkbox"
                                                            checked={formResponses[criterion.id]?.is_available || false}
                                                            onChange={(e) => handleInputChange(criterion.id, 'is_available', e.target.checked)}
                                                            className="form-checkbox h-5 w-5 text-indigo-600 transition duration-150 ease-in-out"
                                                        />
                                                        <span className="ml-2 text-gray-700">Available</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div className="mt-4">
                                                <label className="block text-sm font-medium text-gray-700">
                                                    Notes
                                                </label>
                                                <div className="mt-1">
                          <textarea
                              rows="2"
                              value={formResponses[criterion.id]?.notes || ''}
                              onChange={(e) => handleInputChange(criterion.id, 'notes', e.target.value)}
                              className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                          />
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}

                        <div className="mt-8 flex justify-end">
                            <button
                                type="button"
                                onClick={handleSave}
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Save Assessment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AssessmentPage;
