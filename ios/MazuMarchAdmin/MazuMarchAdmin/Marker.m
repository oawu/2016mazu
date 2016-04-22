//
//  Marker.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "Marker.h"

@implementation Marker

+ (CLLocationCoordinate2D) oriLocation {
    return CLLocationCoordinate2DMake(23.567600533837, 120.30456438661);
}
- (Marker *) initWithDictionary:(NSDictionary *)dictionary map:(GMSMapView *) map {
    self = [super init];
    if (self) {
        self.mapView = map;
        
        self.polyline = [GMSPolyline new];
        [self.polyline setStrokeColor:[UIColor colorWithRed:249/255.0f green:39/255.0f blue:114/255.0f alpha:.5f]];
        [self.polyline setStrokeWidth:3.0f];
        [self.polyline setMap:self.mapView];
        
        CLLocationCoordinate2D lastPoin = kCLLocationCoordinate2DInvalid;
        GMSMutablePath *path = [GMSMutablePath path];
        for (NSDictionary *p in [dictionary objectForKey:@"p"]) {
            CLLocationCoordinate2D temp = CLLocationCoordinate2DMake([[p objectForKey:@"a"] doubleValue], [[p objectForKey:@"n"] doubleValue]);
            [path addCoordinate:temp];
            if (!CLLocationCoordinate2DIsValid (lastPoin)) lastPoin = temp;
        }

        [self.polyline setPath:path];
        
        self.marker = [GMSMarker new];
        [self.marker setTitle:[dictionary objectForKey:@"n"]];
        [self.marker setSnippet:[dictionary objectForKey:@"t"]];
        [self.marker setIcon:nil];
        [self.marker setMap:self.mapView];
        [self.marker setPosition:!CLLocationCoordinate2DIsValid (lastPoin) ? [Marker oriLocation] : lastPoin];
    }
    return self;
}
- (void)cleanAll {
    [self.marker setMap:nil];
    [self.polyline setMap:nil];
}
@end
