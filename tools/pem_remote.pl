#!/usr/bin/perl
#
# pem_remote.pl --base_url=http://talendforge.org/ext --category_id=2 --version=2.4.1

use strict;
use warnings;

use JSON;
use PHP::Serialization qw/unserialize/;
use LWP::UserAgent;
use Text::ASCIITable;
use Getopt::Long;

our $ua = LWP::UserAgent->new;

my %conf;
$conf{base_url} = 'http://localhost/~pierrick/pem/trunk';
$conf{category_id} = 1;
$conf{version} = '2.4.0';
$conf{format} = 'json';

my %opt = ();
GetOptions(
    \%opt,
    qw/base_url=s category_id=i version=s format=s/
);

foreach my $key (keys %opt) {
    $conf{$key} = $opt{$key};
}

my $result = undef;
my $query = undef;

binmode STDOUT, ":encoding(utf-8)";

$query = pem_create_query(
    method => 'get_revision_list',
    version => $conf{version},
    category_id => $conf{category_id},
    format => $conf{format},
);

# print $query, "\n"; exit();

$result = $ua->get($query);
my $revisions = undef;
if ($conf{format} eq 'json') {
    $revisions = from_json($result->content);
}
elsif ($conf{format} eq 'php') {
    $revisions = unserialize($result->content);
}

use Data::Dumper;
# print Dumper($revisions); exit();

my $t = Text::ASCIITable->new({ headingText => 'Components' });
$t->setCols(qw/author date revision component url ext_dl rev_dl/);

foreach my $revision_href (@{$revisions}) {
    $t->addRow(
        $revision_href->{extension_author},
        $revision_href->{revision_date},
        $revision_href->{revision_name},
        $revision_href->{extension_name},
        $revision_href->{download_url},
        $revision_href->{extension_nb_downloads},
        $revision_href->{revision_nb_downloads}
    );
}
print $t;

sub pem_create_query {
    my %params = @_;

    my $query = $conf{base_url}.'/api/'.$params{method}.'.php';

    if (scalar keys %params > 1) {
        $query.= '?';
        $query.= join(
            '&',
            map { $_.'='.$params{$_} } keys %params
        );
    }

    return $query;
}
