"""
A setuptools based setup module.
"""

from setuptools import setup, find_packages

setup(
    name='group_scraper',
    version='1.0.0',
    description='Scraper utility for FitBit',
    packages=find_packages(),
    install_requires=[
        'elasticsearch>=2,<3',
        'psycopg2>=2.7.1',
        'enum34>=1.1.6',
        'urllib3>=1.20',
        'boto3>=1.4.4',
        'requests_aws4auth>=0.9',
    ],
    entry_points={
        'console_scripts': [
            'rgscrape=group_scraper:main',
        ],
    }
)