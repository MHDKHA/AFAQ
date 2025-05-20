// resources/js/Pages/Assessments/Fill.jsx
import React, { useState } from 'react';
import { usePage, useForm } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function Fill({ assessment, domains }) {
    const { auth, locale } = usePage().props;
    const [activeDomain, setActiveDomain] = useState(domains[0]?.id || null);

    // Initialize form with existing responses
    const initialData = {
        responses: assessment.items.reduce((acc, item) => {
            acc[item.criteria_id] = {
                is_available: item.is_available,
                notes: item.notes
            };
            return acc;
        }, {})
    };

    const { data, setData, post, processing, errors } = useForm(initialData);

    const handleResponseChange = (criteriaId, field, value) => {
        setData('responses', {
            ...data.responses,
            [criteriaId]: {
                ...data.responses[criteriaId],
                [field]: value
            }
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('assessment.save', assessment.id));
    };

    // Flatten criteria for easier access
    const allCriteria = domains.flatMap(domain =>
        domain.categories.flatMap(category =>
            category.criteria.map(criterion => ({
                ...criterion,
                domain_id: domain.id,
                category_name: locale === 'ar' ? category.name_ar : category.name
            }))
        )
    );

    // Filter criteria by active domain
    const filteredCriteria = allCriteria.filter(criterion => criterion.domain_id === activeDomain);

    // Group criteria by category
    const groupedCriteria = filteredCriteria.reduce((acc, criterion) => {
        if (!acc[criterion.category_name]) {
            acc[criterion.category_name] = [];
        }
        acc[criterion.category_name].push(criterion);
        return acc;
    }, {});

    return (
        <MainLayout title="Fill Assessment">
            <div className="bg-white shadow-sm rounded-lg overflow-hidden">
                <div className="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h1 className="text-lg font-medium text-gray-900">
                        {locale === 'ar' ? assessment.name_ar : assessment.name}
                    </h1>
                    <p className="mt-1 text-sm text-gray-500">
                        {locale === 'ar' ? assessment.tool.name_ar : assessment.tool.name}
                    </p>
                </div>

                <div className="bg-gray-50 px-4 py-5 sm:p-6">
                    <div className="mb-6">
                        <label htmlFor="domain" className="block text-sm font-medium text-gray-700">
                            Select Domain
                        </label>
                        <select
                            id="domain"
                            className="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            value={activeDomain}
                            onChange={(e) => setActiveDomain(parseInt(e.target.value))}
                        >
                            {domains.map(domain => (
                                <option key={domain.id} value={domain.id}>
                                    {locale === 'ar' ? domain.name_ar : domain.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {Object.entries(groupedCriteria).map(([categoryName, criteria]) => (
                            <div key={categoryName} className="mb-8">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    {categoryName}
                                </h3>

                                <div className="bg-white shadow overflow-hidden sm:rounded-md">
                                    <ul className="divide-y divide-gray-200">
                                        {criteria.map(criterion => {
                                            const criteriaId = criterion.id;
                                            const response = data.responses[criteriaId] || { is_available: false, notes: '' };

                                            return (
                                                <li key={criteriaId} className="px-4 py-4 sm:px-6">
                                                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                                                        <div className="lg:col-span-7">
                                                            <p className="text-sm font-medium text-gray-900">
                                                                {locale === 'ar' ? criterion.question_ar : criterion.question}
                                                            </p>
                                                        </div>

                                                        <div className="lg:col-span-1 flex items-center">
                                                            <div className="flex items-center h-5">
                                                                <input
                                                                    id={`available-${criteriaId}`}
                                                                    type="checkbox"
                                                                    className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                                    checked={response.is_available}
                                                                    onChange={(e) => handleResponseChange(criteriaId, 'is_available', e.target.checked)}
                                                                />
                                                                <label htmlFor={`available-${criteriaId}`} className="ml-2 block text-sm text-gray-900">
                                                                    Available
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div className="lg:col-span-4">
                                                            <textarea
                                                                rows="2"
                                                                className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                                placeholder="Notes"
                                                                value={response.notes || ''}
                                                                onChange={(e) => handleResponseChange(criteriaId, 'notes', e.target.value)}
                                                            />
                                                        </div>
                                                    </div>
                                                </li>
                                            );
                                        })}
                                    </ul>
                                </div>
                            </div>
                        ))}

                        <div className="mt-8 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                {processing ? 'Saving...' : 'Save Progress'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </MainLayout>
    );
}
