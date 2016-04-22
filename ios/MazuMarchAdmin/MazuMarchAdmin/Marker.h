//
//  Marker.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreLocation/CoreLocation.h>
@import GoogleMaps;
@interface Marker : NSObject

@property CLLocationCoordinate2D position;
@property GMSPolyline *polyline;
@property GMSMarker *marker;
@property GMSMapView *mapView;

- (Marker *) initWithDictionary:(NSDictionary *)dictionary map:(GMSMapView *) map;
- (void)cleanAll;
@end
